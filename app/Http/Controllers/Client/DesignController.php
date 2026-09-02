<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientPortalDesign;
use App\Models\ClientPortalDesignComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    // ── Every query below is scoped to the authenticated client via the
    // ── owning project's client_user_id. A design that doesn't belong to
    // ── the logged-in client simply does not exist as far as this
    // ── controller is concerned — findOrFail() on a scoped query yields a
    // ── 404, never a 403, so IDs can't be used to fingerprint other clients.

    public function index()
    {
        $userId = Auth::id();

        $designs = ClientPortalDesign::whereHas('project', function ($q) use ($userId) {
                $q->where('client_user_id', $userId);
            })
            ->with('project')
            ->latest()
            ->get()
            ->groupBy('client_portal_project_id');

        return view('client.designs.index', compact('designs'));
    }

    public function show(string $design)
    {
        $userId = Auth::id();

        $design = ClientPortalDesign::whereHas('project', function ($q) use ($userId) {
                $q->where('client_user_id', $userId);
            })
            ->with(['project', 'comments.user'])
            ->findOrFail($design);

        $versions = ClientPortalDesign::where('client_portal_project_id', $design->client_portal_project_id)
            ->orderByDesc('created_at')
            ->get();

        return view('client.designs.show', compact('design', 'versions'));
    }

    public function storeComment(Request $request, string $design)
    {
        $userId = Auth::id();

        $design = ClientPortalDesign::whereHas('project', function ($q) use ($userId) {
                $q->where('client_user_id', $userId);
            })
            ->findOrFail($design);

        $data = $request->validate([
            'body'       => 'required|string|max:2000',
            'x_position' => 'nullable|numeric|min:0|max:100',
            'y_position' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            ClientPortalDesignComment::create([
                'client_portal_design_id' => $design->id,
                'user_id'                 => $userId,
                'body'                    => $data['body'],
                'x_position'              => $data['x_position'] ?? null,
                'y_position'              => $data['y_position'] ?? null,
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Client DesignController@storeComment failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not post comment.']);
        }

        return back()->with('success', 'Comment added.');
    }

    public function approve(string $design)
    {
        $userId = Auth::id();

        $design = ClientPortalDesign::whereHas('project', function ($q) use ($userId) {
                $q->where('client_user_id', $userId);
            })
            ->findOrFail($design);

        $design->update(['status' => 'approved']);

        return back()->with('success', 'Design approved.');
    }

    public function requestChanges(Request $request, string $design)
    {
        $userId = Auth::id();

        $design = ClientPortalDesign::whereHas('project', function ($q) use ($userId) {
                $q->where('client_user_id', $userId);
            })
            ->findOrFail($design);

        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        DB::beginTransaction();
        try {
            ClientPortalDesignComment::create([
                'client_portal_design_id' => $design->id,
                'user_id'                 => $userId,
                'body'                    => $data['body'],
            ]);
            $design->update(['status' => 'changes_requested']);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Client DesignController@requestChanges failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not submit change request.']);
        }

        return back()->with('success', 'Changes requested.');
    }
}
