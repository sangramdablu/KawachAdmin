<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleMeta;
use Spatie\Permission\Models\Role;

/**
 * Read-only "Team" page — a filtered view onto the existing Users system
 * rather than a separate identity. A user becomes a displayed team member
 * purely via the `is_team_member` flag on the users table (toggled through
 * the existing Edit User modal in roles/index.blade.php, handled by
 * RoleAccessController::updateUser()). This controller intentionally does
 * NOT duplicate that create/update logic — it only lists.
 */
class TeamController extends Controller
{
    public function index()
    {
        $members = User::with('roles')
            ->where('is_team_member', true)
            ->orderBy('name')
            ->get()
            ->map(function (User $user) {
                $role = $user->roles->first();
                $meta = $role ? RoleMeta::where('role_name', $role->name)->first() : null;

                $nameParts = explode(' ', trim($user->name));
                $initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                $colours   = ['#6c2bd9', '#1a73e8', '#00c896', '#e91e8c', '#ff6d00', '#00bcd4', '#673ab7', '#f44336', '#4caf50', '#ff9800'];
                $avatarBg  = $colours[$user->id % count($colours)];

                return [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'email'            => $user->email,
                    'avatarUrl'        => $user->avatar ? asset($user->avatar) : null,
                    'avatarInitials'   => $initials,
                    'avatarBg'         => $avatarBg,
                    'designation'      => $user->designation,
                    'teamRole'         => $user->team_role,
                    'responsibilities' => $user->responsibilities,
                    'permissionRole'   => $role?->name ?? 'viewer',
                    'roleColor'        => $meta?->color ?? 'viewer',
                    'status'           => $user->status ?? 'active',
                ];
            });

        // Role names for the Invite Team Member modal's "Assign Role" select —
        // same list roles/index.blade.php uses for its own Invite User modal.
        $roleNames = Role::orderBy('name')->pluck('name');

        return view('team.index', compact('members', 'roleNames'));
    }
}
