<?php

namespace App\Http\Controllers;

use App\Models\ClientPortalInvoice;
use App\Models\ClientPortalProject;
use App\Models\ClientPortalTask;
use App\Models\ClientPortalTeam;
use Illuminate\Support\Facades\Auth;

class ClientPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Projects ──────────────────────────────────────────────────────
        // Load all portal projects belonging to this client, with their
        // tasks and team members eager-loaded.
        $portalProjects = ClientPortalProject::with(['tasks', 'team'])
            ->where('client_user_id', $user->id)
            ->latest()
            ->get();

        // Shape projects into the array the Blade template expects
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

        // ── Pending tasks (client approval/input needed) ──────────────────
        $projectIds = $portalProjects->pluck('id');

        $pendingTasksList = ClientPortalTask::whereIn('client_portal_project_id', $projectIds)
            ->where('state', '!=', 'done')
            ->orWhere(function ($q) use ($projectIds) {
                // Also pull recently completed so client sees "Done" items
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

        // ── Team (unique members across all projects) ─────────────────────
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

        // ── Invoices ──────────────────────────────────────────────────────
        $invoices = ClientPortalInvoice::where('client_user_id', $user->id)
            ->latest('invoice_date')
            ->get()
            ->map(fn (ClientPortalInvoice $inv) => [
                'id'         => $inv->invoice_id,
                'date'       => $inv->date_formatted,
                'amount'     => $inv->amount_formatted,
                'status'     => $inv->status,
                'icon_bg'    => $inv->icon_bg,
                'icon_color' => $inv->icon_color,
            ])->all();

        // ── Summary counters for banner & stat cards ──────────────────────
        $totalProjects    = count($projects);
        $completedTasks   = ClientPortalTask::whereIn('client_portal_project_id', $projectIds)
                                ->where('state', 'done')->count();
        $pendingApprovals = ClientPortalTask::whereIn('client_portal_project_id', $projectIds)
                                ->where('state', 'active')->count();
        $pendingInvoices  = ClientPortalInvoice::where('client_user_id', $user->id)
                                ->whereIn('status', ['due', 'overdue'])->count();
        $activeTasks      = ClientPortalTask::whereIn('client_portal_project_id', $projectIds)
                                ->where('state', '!=', 'done')->count();

        // Days until next milestone (nearest project deadline in the future)
        $nextDeadline        = $portalProjects
            ->filter(fn ($p) => $p->deadline && $p->deadline->isFuture())
            ->sortBy('deadline')
            ->first();
        $nextMilestoneDays   = $nextDeadline
            ? (int) now()->diffInDays($nextDeadline->deadline)
            : null;

        return view('client.portal', compact(
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
}