<?php

namespace App\Http\Controllers;

use App\Models\ClientPortalBug;
use App\Models\ClientPortalChangeRequest;
use App\Models\ClientPortalDesign;
use App\Models\ClientPortalInvoice;
use App\Models\ClientPortalProject;
use App\Models\ClientPortalTask;
use App\Models\ClientPortalTeam;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    // ── List all client users ─────────────────────────────────────────────


    public function index()
    {
        $clients = User::role('client')
            ->with([
                'clientPortalProjects.tasks',
                'clientPortalProjects.team',
            ])
            ->latest()
            ->paginate(20);

        // ALL PROJECTS
        $portalProjects = ClientPortalProject::with([
            'tasks',
            'team',
            'clientUser',
        ])->latest()->get();

        $projectIds = $portalProjects->pluck('id');

        // PROJECTS ARRAY
        $projects = $portalProjects->map(function (ClientPortalProject $p) {
            return [
                'name'         => $p->project_name,
                'type'         => $p->project_type ?? 'Web Project',
                'icon'         => $p->icon,
                'color_bg'     => $p->color_bg,
                'color'        => $p->color,
                'status'       => $p->status,
                'status_label' => $p->status_label,
                'badge_class'  => $p->badge_class,
                'progress'     => $p->progress,
                'phases'       => $p->phases ?? [],
                'tags'         => $p->tags ?? [],
                'start_date'   => $p->start_date_formatted,
                'deadline'     => $p->deadline_formatted,
                'done_tasks'   => $p->done_tasks,
                'total_tasks'  => $p->total_tasks,
            ];
        })->all();

        // TASKS
        $pendingTasksList = ClientPortalTask::where(function ($query) use ($projectIds) {

            $query->whereIn('client_portal_project_id', $projectIds)
                ->where('state', '!=', 'done');

        })->orWhere(function ($q) use ($projectIds) {

            $q->whereIn('client_portal_project_id', $projectIds)
                ->where('state', 'done')
                ->whereDate('updated_at', '>=', now()->subDays(7));

        })
        ->orderByRaw("FIELD(state, 'active', 'pending', 'done')")
        ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
        ->get()
        ->map(function (ClientPortalTask $t) use ($portalProjects) {

            $projectName = $portalProjects
                ->firstWhere('id', $t->client_portal_project_id)
                ?->project_name ?? '—';

            return [
                'name'     => $t->name,
                'state'    => $t->state,
                'priority' => $t->priority,
                'due'      => $t->due_formatted,
                'overdue'  => $t->overdue,
                'project'  => $projectName,
            ];
        })->all();

        // TEAM
        $team = ClientPortalTeam::whereIn('client_portal_project_id', $projectIds)
            ->orderBy('sort_order')
            ->get()
            ->unique('name')
            ->map(fn (ClientPortalTeam $m) => [
                'name'     => $m->name,
                'role'     => $m->role,
                'initials' => $m->initials,
                'color'    => $m->color,
                'status'   => $m->status,
            ])->values()->all();

        // INVOICES
        $invoices = ClientPortalInvoice::latest('invoice_date')
            ->get()
            ->map(fn (ClientPortalInvoice $inv) => [
                'id'         => $inv->invoice_id,
                'date'       => $inv->date_formatted,
                'amount'     => $inv->amount_formatted,
                'status'     => $inv->status,
                'icon_bg'    => $inv->icon_bg,
                'icon_color' => $inv->icon_color,
            ])->all();

        // STATS
        $totalProjects = count($projects);

        $completedTasks = ClientPortalTask::whereIn(
            'client_portal_project_id',
            $projectIds
        )
        ->where('state', 'done')
        ->count();

        $pendingApprovals = ClientPortalTask::whereIn(
            'client_portal_project_id',
            $projectIds
        )
        ->where('state', 'active')
        ->count();

        $pendingInvoices = ClientPortalInvoice::whereIn(
            'status',
            ['due', 'overdue']
        )->count();

        $activeTasks = ClientPortalTask::whereIn(
            'client_portal_project_id',
            $projectIds
        )
        ->where('state', '!=', 'done')
        ->count();

        // NEXT DEADLINE
        $nextDeadline = $portalProjects
            ->filter(fn ($p) => $p->deadline && $p->deadline->isFuture())
            ->sortBy('deadline')
            ->first();

        $nextMilestoneDays = $nextDeadline
            ? (int) now()->diffInDays($nextDeadline->deadline)
            : null;

        return view('clients.index', compact(
            'clients',
            'projects',
            'pendingTasksList',
            'team',
            'invoices',
            'totalProjects',
            'completedTasks',
            'pendingApprovals',
            'pendingInvoices',
            'activeTasks',
            'nextMilestoneDays',
        ));
    }

    // public function index()
    // {
    //     $clients = User::role('client')
    //         ->with(['clientPortalProjects'])
    //         ->latest()
    //         ->paginate(20);

    //     return view('clients.index', compact('clients'));
    // }

    // ── Create form ───────────────────────────────────────────────────────
    public function create()
    {
        return view('clients.create');
    }

    // ── Store new client manually (without billing agreement) ─────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->assignRole('client');
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@store failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not create client.'])->withInput();
        }

        return redirect()->route('clients.index')
                         ->with('success', 'Client created successfully.');
    }

    // ── Edit form (client user + their projects) ──────────────────────────
    public function edit(string $id)
    {
        $client   = User::role('client')->with('clientPortalProjects.tasks', 'clientPortalProjects.team')->findOrFail($id);
        $projects = $client->clientPortalProjects;

        return view('clients.edit', compact('client', 'projects'));
    }

    // ── Update client user details ────────────────────────────────────────
    public function update(Request $request, string $id)
    {
        $client = User::role('client')->findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $client->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $client->name  = $data['name'];
            $client->email = $data['email'];
            if (! empty($data['password'])) {
                $client->password = Hash::make($data['password']);
            }
            $client->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@update failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not update client.'])->withInput();
        }

        return redirect()->route('clients.index')
                         ->with('success', 'Client updated successfully.');
    }

    // ── Soft-delete client ────────────────────────────────────────────────
    public function destroy(string $id)
    {
        $client = User::role('client')->findOrFail($id);

        DB::beginTransaction();
        try {
            $client->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@destroy failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not delete client.']);
        }

        return redirect()->route('clients.index')
                         ->with('success', 'Client removed.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // PROJECT PROGRESS MANAGEMENT (admin updates the project card)
    // ══════════════════════════════════════════════════════════════════════

    public function updateProject(Request $request, string $projectId)
    {
        $project = ClientPortalProject::findOrFail($projectId);

        $data = $request->validate([
            'status'     => 'required|in:inprogress,active,review,onhold,completed',
            'progress'   => 'required|integer|min:0|max:100',
            'phases'     => 'nullable|array',
            'phases.*.name'  => 'required|string|max:50',
            'phases.*.state' => 'required|in:done,active,pending',
            'done_tasks' => 'required|integer|min:0',
            'total_tasks'=> 'required|integer|min:0',
            'deadline'   => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $project->update($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@updateProject failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not update project.']);
        }

        return back()->with('success', 'Project progress updated.');
    }

    // ── Add or update a pending task (client action item) ─────────────────
    public function storeTask(Request $request, string $projectId)
    {
        $project = ClientPortalProject::findOrFail($projectId);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'priority' => 'required|in:high,medium,low',
            'due'      => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            ClientPortalTask::create([
                'client_portal_project_id' => $project->id,
                'name'     => $data['name'],
                'state'    => 'active',
                'priority' => $data['priority'],
                'due'      => $data['due'],
                'overdue'  => now()->gt($data['due']),
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@storeTask failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not create task.']);
        }

        return back()->with('success', 'Task added.');
    }

    // ── Add team member to a project ──────────────────────────────────────
    public function storeTeamMember(Request $request, string $projectId)
    {
        $project = ClientPortalProject::findOrFail($projectId);

        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'role'     => 'required|string|max:100',
            'initials' => 'required|string|max:4',
            'color'    => 'required|string|max:30',
            'status'   => 'required|in:online,offline,away',
        ]);

        DB::beginTransaction();
        try {
            ClientPortalTeam::create([
                'client_portal_project_id' => $project->id,
                ...$data,
                'sort_order' => ClientPortalTeam::where('client_portal_project_id', $project->id)->max('sort_order') + 1,
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@storeTeamMember failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not add team member.']);
        }

        return back()->with('success', 'Team member added.');
    }

    // ── Add invoice ───────────────────────────────────────────────────────
    public function storeInvoice(Request $request, string $clientId)
    {
        $client = User::role('client')->findOrFail($clientId);

        $data = $request->validate([
            'invoice_id'      => 'required|string|max:50',
            'invoice_date'    => 'required|date',
            'amount_cents'    => 'required|integer|min:1',
            'currency_symbol' => 'required|string|max:5',
            'status'          => 'required|in:paid,due,overdue',
        ]);

        DB::beginTransaction();
        try {
            ClientPortalInvoice::create([
                'client_user_id' => $client->id,
                ...$data,
                'icon_bg'    => match($data['status']) {
                    'paid'    => '#d4f5ec',
                    'due'     => '#fff4d6',
                    'overdue' => '#ffe2e8',
                },
                'icon_color' => match($data['status']) {
                    'paid'    => '#00a87c',
                    'due'     => '#b8860b',
                    'overdue' => 'var(--danger)',
                },
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@storeInvoice failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not create invoice.']);
        }

        return back()->with('success', 'Invoice added.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // SINGLE-PROJECT ADMIN WORKSPACE (tasks / team / invoices / designs /
    // change requests / bugs — one page per ClientPortalProject)
    // ══════════════════════════════════════════════════════════════════════

    public function showProject(string $projectId)
    {
        $project = ClientPortalProject::with([
            'clientUser',
            'tasks',
            'team',
            'designs.comments',
            'changeRequests',
            'bugs',
        ])->findOrFail($projectId);

        $invoices = ClientPortalInvoice::where('client_user_id', $project->client_user_id)
            ->latest('invoice_date')
            ->get();

        return view('clients.project', compact('project', 'invoices'));
    }

    // ── Upload a design for client review ──────────────────────────────────
    public function storeDesign(Request $request, string $projectId)
    {
        $project = ClientPortalProject::findOrFail($projectId);

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'version'    => 'nullable|string|max:30',
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'figma_url'  => 'nullable|url|max:500',
        ]);

        $upload = app(ImageUploadService::class)->uploadToPublic($request->file('image'), 'client_designs');

        DB::beginTransaction();
        try {
            ClientPortalDesign::create([
                'client_portal_project_id' => $project->id,
                'title'                    => $data['title'],
                'version'                  => $data['version'] ?? 'v1',
                'image_path'               => $upload['path'],
                'figma_url'                => $data['figma_url'] ?? null,
                'status'                   => 'pending',
                'uploaded_by'              => auth()->id(),
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@storeDesign failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not upload design.']);
        }

        return back()->with('success', 'Design uploaded.');
    }

    // ── Respond to a change request with an estimate / cost / impact ───────
    public function respondChangeRequest(Request $request, string $projectId, string $changeRequestId)
    {
        $changeRequest = ClientPortalChangeRequest::where('client_portal_project_id', $projectId)
            ->findOrFail($changeRequestId);

        $data = $request->validate([
            'estimated_hours' => 'nullable|numeric|min:0|max:9999',
            'additional_cost' => 'nullable|numeric|min:0|max:9999999',
            'deadline_impact' => 'nullable|string|max:100',
            'response_note'   => 'nullable|string|max:2000',
        ]);

        DB::beginTransaction();
        try {
            $changeRequest->update([
                ...$data,
                'responded_by' => auth()->id(),
                'responded_at' => now(),
                'status'       => 'responded',
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClientController@respondChangeRequest failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not save response.']);
        }

        return back()->with('success', 'Response sent to client.');
    }

    // ── Update a bug's workflow status ──────────────────────────────────────
    public function updateBugStatus(Request $request, string $projectId, string $bugId)
    {
        $bug = ClientPortalBug::where('client_portal_project_id', $projectId)
            ->findOrFail($bugId);

        $data = $request->validate([
            'status' => 'required|in:reported,under_review,in_progress,fixed,ready_for_testing,closed',
        ]);

        $bug->update($data);

        return back()->with('success', 'Bug status updated.');
    }
}