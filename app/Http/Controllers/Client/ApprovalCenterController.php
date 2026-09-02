<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientPortalChangeRequest;
use App\Models\ClientPortalDesign;
use Illuminate\Support\Facades\Auth;

class ApprovalCenterController extends Controller
{
    // ── Decisions-needed view: pending designs + responded change requests ──
    public function index()
    {
        $userId = Auth::id();

        $pendingDesigns = ClientPortalDesign::whereHas('project', function ($q) use ($userId) {
                $q->where('client_user_id', $userId);
            })
            ->with('project')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $respondedChangeRequests = ClientPortalChangeRequest::where('client_user_id', $userId)
            ->where('status', 'responded')
            ->with('project')
            ->latest('responded_at')
            ->get();

        return view('client.approvals', compact('pendingDesigns', 'respondedChangeRequests'));
    }
}
