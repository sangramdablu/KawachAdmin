<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\RoleMeta;
use App\Models\AccessActivityLog;
use App\Models\UserInvitation;
use Database\Seeders\RolesAndPermissionsSeeder;
use App\Services\InvitationService;

class RoleAccessController extends Controller
{
    // ── Guard ────────────────────────────────────────────────────
    // Only super-admin and admin can access this controller.
    // Add ->middleware('role:super-admin|admin') on the route group.

    /* ════════════════════════════════════════════════════════
       INDEX  —  Render the full roles & access management page
    ════════════════════════════════════════════════════════ */
    public function index(): \Illuminate\View\View
    {
        // Stats
        $stats = [
            'total_users'    => User::count(),
            'active_roles'   => Role::count(),
            'active_users'   => User::where('status', 'active')->count(),
            'inactive_users' => User::whereIn('status', ['inactive', 'banned'])->count(),
            'total_perms'    => Permission::count(),
        ];

        // Users with their roles (eager loaded)
        $users = User::with('roles')
            ->latest()
            ->get()
            ->map(fn($u) => $this->formatUser($u));

        // Roles with meta + user counts
        $roles = $this->getRolesWithMeta();

        // Permissions grouped by section
        $permSections = $this->getPermissionSections();

        // Role permission map  { role_name => [perm.key, ...] }
        $rolePerms = $this->getRolePermissionsMap();

        // Role names list for filters/selects
        $roleNames = Role::orderBy('name')->pluck('name');

        // Activity log (latest 50)
        $activity = AccessActivityLog::latest()->limit(50)->get()
            ->map(fn($a) => [
                'type'        => $a->type,
                'description' => $a->description,
                'time'        => $a->time_ago,
            ]);

        return view('roles.index', compact(
            'stats', 'users', 'roles', 'permSections', 'rolePerms', 'roleNames', 'activity'
        ));
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  List (AJAX, with search/filter/pagination)
    ════════════════════════════════════════════════════════ */
    public function usersData(Request $request): JsonResponse
    {
        $query = User::with('roles')
            ->when($request->search, function ($q, $s) {
                $q->where(function ($q2) use ($s) {
                    $q2->where('name', 'like', "%{$s}%")
                       ->orWhere('email', 'like', "%{$s}%");
                });
            })
            ->when($request->role, function ($q, $r) {
                $q->whereHas('roles', fn($q2) => $q2->where('name', $r));
            })
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest();

        $users = $query->paginate(20);

        return response()->json([
            'data'  => $users->map(fn($u) => $this->formatUser($u)),
            'total' => $users->total(),
            'pages' => $users->lastPage(),
        ]);
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  Update a single user's role + status
    ════════════════════════════════════════════════════════ */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role'   => ['required', 'string', Rule::exists('roles', 'name')],
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $oldRole = $user->roles->first()?->name ?? '—';

        DB::beginTransaction();
        try {
            // Sync role via Spatie
            $user->syncRoles([$validated['role']]);
            $user->update(['status' => $validated['status']]);

            // Log
            if ($oldRole !== $validated['role']) {
                AccessActivityLog::record(
                    'blue',
                    "<strong>" . auth()->user()->name . "</strong> changed <strong>{$user->name}</strong>'s role from {$oldRole} → {$validated['role']}"
                );
            }
            if ($validated['status'] === 'inactive') {
                AccessActivityLog::record('red', "<strong>" . auth()->user()->name . "</strong> deactivated <strong>{$user->name}</strong>");
            }

            DB::commit();
            return response()->json(['success' => true, 'user' => $this->formatUser($user->fresh('roles'))]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('RoleAccessController@updateUser: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  Toggle ban / unban
    ════════════════════════════════════════════════════════ */
    public function toggleBan(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot ban yourself.'], 422);
        }

        $newStatus = $user->status === 'inactive' ? 'active' : 'inactive';
        $user->update(['status' => $newStatus]);

        AccessActivityLog::record(
            $newStatus === 'inactive' ? 'red' : 'green',
            "<strong>" . auth()->user()->name . "</strong> " . ($newStatus === 'inactive' ? 'banned' : 'unbanned') . " <strong>{$user->name}</strong>"
        );

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  Remove user from system
    ════════════════════════════════════════════════════════ */
    public function removeUser(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 422);
        }

        $name = $user->name;
        $user->delete();

        AccessActivityLog::record('red', "<strong>" . auth()->user()->name . "</strong> deleted user <strong>{$name}</strong>");

        return response()->json(['success' => true, 'message' => "{$name} removed successfully."]);
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  Send password reset email
    ════════════════════════════════════════════════════════ */
    public function resetPassword(User $user): JsonResponse
    {
        try {
            // Uses Laravel's built-in password reset broker
            $status = \Illuminate\Support\Facades\Password::sendResetLink(['email' => $user->email]);
            AccessActivityLog::record('yellow', "<strong>" . auth()->user()->name . "</strong> sent password reset to <strong>{$user->email}</strong>");
            return response()->json(['success' => true, 'message' => "Reset link sent to {$user->email}"]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send reset email.'], 500);
        }
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  Bulk actions (role change / deactivate)
    ════════════════════════════════════════════════════════ */
    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action'  => ['required', Rule::in(['change_role', 'deactivate', 'activate'])],
            'ids'     => 'required|array|min:1',
            'ids.*'   => 'integer|exists:users,id',
            'role'    => 'required_if:action,change_role|nullable|exists:roles,name',
        ]);

        $ids    = array_filter($request->ids, fn($id) => $id !== auth()->id()); // never affect self
        $action = $request->action;

        DB::beginTransaction();
        try {
            $users = User::whereIn('id', $ids)->get();

            foreach ($users as $user) {
                match ($action) {
                    'change_role' => $user->syncRoles([$request->role]),
                    'deactivate'  => $user->update(['status' => 'inactive']),
                    'activate'    => $user->update(['status' => 'active']),
                };
            }

            $msg = match ($action) {
                'change_role' => "Bulk role change to '{$request->role}' applied to " . count($ids) . " user(s)",
                'deactivate'  => "Bulk deactivated " . count($ids) . " user(s)",
                'activate'    => "Bulk activated "   . count($ids) . " user(s)",
            };

            AccessActivityLog::record('blue', "<strong>" . auth()->user()->name . "</strong>: {$msg}");
            DB::commit();

            return response()->json(['success' => true, 'message' => $msg . '.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ════════════════════════════════════════════════════════
       USERS  —  Export CSV
    ════════════════════════════════════════════════════════ */
    public function exportUsers(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $users = User::with('roles')->get();

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Status', 'Joined']);
            foreach ($users as $u) {
                fputcsv($handle, [
                    $u->id,
                    $u->name,
                    $u->email,
                    $u->roles->first()?->name ?? '—',
                    $u->status ?? 'active',
                    $u->created_at->format('d M Y'),
                ]);
            }
            fclose($handle);
        }, 'users-export-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /* ════════════════════════════════════════════════════════
       ROLES  —  Create
    ════════════════════════════════════════════════════════ */
    public function storeRole(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:80|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'color'       => ['required', Rule::in(['superadmin','admin','editor','viewer','custom'])],
            'icon'        => 'required|string|max:60',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            // Create Spatie role
            $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

            // Assign permissions
            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            // Store metadata
            RoleMeta::create([
                'role_name'   => $validated['name'],
                'color'       => $validated['color'],
                'icon'        => $validated['icon'],
                'description' => $validated['description'] ?? '',
                'is_system'   => false,
            ]);

            AccessActivityLog::record('yellow', "<strong>" . auth()->user()->name . "</strong> created role <strong>{$role->name}</strong>");

            DB::commit();

            return response()->json([
                'success' => true,
                'role'    => $this->formatRole($role),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('storeRole: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ════════════════════════════════════════════════════════
       ROLES  —  Update (name, description, color, icon, permissions)
    ════════════════════════════════════════════════════════ */
    public function updateRole(Request $request, Role $role): JsonResponse
    {
        // System roles cannot be renamed or have their core perms altered
        $meta = RoleMeta::where('role_name', $role->name)->first();

        $rules = [
            'description' => 'nullable|string|max:500',
            'color'       => ['required', Rule::in(['superadmin','admin','editor','viewer','custom'])],
            'icon'        => 'required|string|max:60',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];

        if (!($meta?->is_system)) {
            // Non-system roles can be renamed
            $rules['name'] = ['required', 'string', 'max:80', Rule::unique('roles', 'name')->ignore($role->id)];
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Update role name (only for non-system)
            if (!($meta?->is_system) && isset($validated['name'])) {
                // Update role_meta foreign key first
                RoleMeta::where('role_name', $role->name)->update(['role_name' => $validated['name']]);
                $role->update(['name' => $validated['name']]);
            }

            // Sync permissions (super-admin keeps all)
            if ($role->name !== 'super-admin' && isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            // Update meta
            RoleMeta::updateOrCreate(
                ['role_name' => $role->name],
                [
                    'color'       => $validated['color'],
                    'icon'        => $validated['icon'],
                    'description' => $validated['description'] ?? ($meta?->description ?? ''),
                ]
            );

            AccessActivityLog::record('blue', "<strong>" . auth()->user()->name . "</strong> updated role <strong>{$role->name}</strong>");

            DB::commit();

            return response()->json(['success' => true, 'role' => $this->formatRole($role->fresh())]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /*  ROLES  —  Delete */
    public function destroyRole(Role $role): JsonResponse
    {
        $meta = RoleMeta::where('role_name', $role->name)->first();

        if ($meta?->is_system) {
            return response()->json(['success' => false, 'message' => 'System roles cannot be deleted.'], 422);
        }

        $userCount = $role->users()->count();
        if ($userCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete: {$userCount} user(s) are assigned to this role.",
            ], 422);
        }

        $roleName = $role->name;
        $role->delete();
        RoleMeta::where('role_name', $roleName)->delete();

        AccessActivityLog::record('red', "<strong>" . auth()->user()->name . "</strong> deleted role <strong>{$roleName}</strong>");

        return response()->json(['success' => true, 'message' => "Role '{$roleName}' deleted."]);
    }

    /* ════════════════════════════════════════════════════════
       PERMISSIONS  —  Save matrix (bulk update role permissions)
    ════════════════════════════════════════════════════════ */
    public function savePermissionMatrix(Request $request): JsonResponse
    {
        $request->validate([
            'matrix'          => 'required|array',
            'matrix.*'        => 'array',
            'matrix.*.*'      => 'boolean',
        ]);

        // matrix shape: { role_name: { perm_name: bool } }
        DB::beginTransaction();
        try {
            foreach ($request->matrix as $roleName => $perms) {
                $role = Role::where('name', $roleName)->first();
                if (!$role || $roleName === 'super-admin') continue;

                $toAssign = array_keys(array_filter($perms, fn($v) => $v === true));
                $role->syncPermissions($toAssign);
            }

            // Flush Spatie's permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            AccessActivityLog::record('blue', "<strong>" . auth()->user()->name . "</strong> saved the permission matrix");

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Permission matrix saved successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ════════════════════════════════════════════════════════
       INVITATIONS  —  Send
    ════════════════════════════════════════════════════════ */
    public function sendInvitation(Request $request, InvitationService $invitationService): JsonResponse {
        $validated = $request->validate([
            'email'      => 'required|email|max:200',
            'first_name' => 'nullable|string|max:80',
            'last_name'  => 'nullable|string|max:80',
            'role'       => ['required', 'string', Rule::exists('roles', 'name')],
            'message'    => 'nullable|string|max:1000',
        ]);
        DB::beginTransaction();
        try {
            $invitation = $invitationService->createAndSend($validated);
            AccessActivityLog::record('green', "<strong>" . auth()->user()->name . "</strong> invited <strong>{$invitation->email}</strong> as {$invitation->role_name}");
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Invitation sent to {$invitation->email}",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /* ════════════════════════════════════════════════════════
       ACTIVITY LOG  —  Paginated list (AJAX)
    ════════════════════════════════════════════════════════ */
    public function activityLog(Request $request): JsonResponse
    {
        $query = AccessActivityLog::latest()
            ->when($request->search, fn($q, $s) =>
                $q->where('description', 'like', "%{$s}%")
            );

        $logs = $query->paginate(30);

        return response()->json([
            'data'  => $logs->map(fn($l) => [
                'type'        => $l->type,
                'description' => $l->description,
                'time'        => $l->time_ago,
            ]),
            'total' => $logs->total(),
        ]);
    }

    /* ════════════════════════════════════════════════════════
       PRIVATE HELPERS
    ════════════════════════════════════════════════════════ */

    /**
     * Format a User model for JSON/blade consumption.
     */
    private function formatUser(User $user): array
    {
        $role      = $user->roles->first();
        $meta      = $role ? RoleMeta::where('role_name', $role->name)->first() : null;
        $nameParts = explode(' ', $user->name);
        $initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

        // Generate a deterministic avatar background colour from the user id
        $colours = ['#6c2bd9','#1a73e8','#00c896','#e91e8c','#ff6d00','#00bcd4','#673ab7','#f44336','#4caf50','#ff9800'];
        $avatarBg = $colours[$user->id % count($colours)];

        return [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $role?->name ?? 'viewer',
            'roleColor' => $meta?->color ?? 'viewer',
            'status'    => $user->status ?? 'active',
            'last'      => $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never',
            'joined'    => $user->created_at->format('M Y'),
            'avatar'    => $initials,
            'avatarBg'  => $avatarBg,
        ];
    }

    /**
     * Build roles array with meta + user count for the blade/JS.
     */
    private function getRolesWithMeta(): array
    {
        $metas = RoleMeta::all()->keyBy('role_name');

        return Role::withCount('users')->get()->map(function ($role) use ($metas) {
            $meta = $metas->get($role->name);
            return [
                'id'        => $role->id,
                'name'      => $role->name,
                'color'     => $meta?->color     ?? 'viewer',
                'icon'      => $meta?->icon      ?? 'fas fa-user',
                'desc'      => $meta?->description ?? '',
                'users'     => $role->users_count,
                'is_system' => $meta?->is_system ?? false,
            ];
        })->values()->toArray();
    }

    /**
     * Permissions grouped by section for the permission matrix.
     */
    private function getPermissionSections(): array
    {
        return collect(RolesAndPermissionsSeeder::PERMISSIONS)
            ->map(fn($perms, $section) => [
                'section' => $section,
                'perms'   => collect($perms)->map(fn($label, $key) => [
                    'key'  => $key,
                    'name' => $label,
                    'desc' => '', // add descriptions here if needed
                ])->values()->toArray(),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Map of role_name => [permission_name, ...] from the DB.
     */
    private function getRolePermissionsMap(): array
    {
        return Role::with('permissions')->get()
            ->mapWithKeys(fn($role) => [
                $role->name => $role->permissions->pluck('name')->toArray(),
            ])
            ->toArray();
    }
}

/*
 * ─────────────────────────────────────────────────────────────
 *  USER INVITATION MAIL  (create this file separately)
 * ─────────────────────────────────────────────────────────────
 *  php artisan make:mail UserInvitationMail --markdown=emails.invitation
 *
 *  In App\Mail\UserInvitationMail:
 *    public function __construct(public UserInvitation $invitation) {}
 *    public function content(): Content {
 *        return new Content(markdown: 'emails.invitation', with: [
 *            'url'     => url('/register?token=' . $this->invitation->token),
 *            'role'    => $this->invitation->role_name,
 *            'message' => $this->invitation->message,
 *        ]);
 *    }
 * ─────────────────────────────────────────────────────────────
 */