<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientPortalChangeRequest;
use App\Models\ClientPortalProject;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChangeRequestController extends Controller
{
    public function index()
    {
        $changeRequests = ClientPortalChangeRequest::where('client_user_id', Auth::id())
            ->with('project')
            ->latest()
            ->get();

        return view('client.change-requests.index', compact('changeRequests'));
    }

    public function create()
    {
        $projects = ClientPortalProject::where('client_user_id', Auth::id())->get();

        return view('client.change-requests.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_portal_project_id' => 'required|integer',
            'title'                    => 'required|string|max:255',
            'description'              => 'required|string|max:5000',
            'priority'                 => 'required|in:low,medium,high',
            'expected_result'          => 'nullable|string|max:2000',
            'screenshot'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        // Never trust the submitted project id blindly — re-check ownership.
        $project = ClientPortalProject::where('id', $data['client_portal_project_id'])
            ->where('client_user_id', Auth::id())
            ->firstOrFail();

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $upload = app(ImageUploadService::class)->uploadToPublic($request->file('screenshot'), 'client_change_requests');
            $screenshotPath = $upload['path'];
        }

        DB::beginTransaction();
        try {
            ClientPortalChangeRequest::create([
                'client_portal_project_id' => $project->id,
                'client_user_id'           => Auth::id(),
                'title'                    => $data['title'],
                'description'              => $data['description'],
                'priority'                 => $data['priority'],
                'expected_result'          => $data['expected_result'] ?? null,
                'screenshot_path'          => $screenshotPath,
                'status'                   => 'submitted',
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Client ChangeRequestController@store failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not submit change request.'])->withInput();
        }

        return redirect()->route('client.change-requests.index')->with('success', 'Change request submitted.');
    }

    public function show(string $changeRequest)
    {
        $cr = ClientPortalChangeRequest::where('client_user_id', Auth::id())
            ->with(['project', 'responder'])
            ->findOrFail($changeRequest);

        return view('client.change-requests.show', ['cr' => $cr]);
    }

    public function approve(string $changeRequest)
    {
        $cr = ClientPortalChangeRequest::where('client_user_id', Auth::id())->findOrFail($changeRequest);

        if ($cr->status !== 'responded') {
            return back()->withErrors(['error' => 'This request is not awaiting a decision.']);
        }

        $cr->update(['status' => 'approved']);

        return back()->with('success', 'Change request approved.');
    }

    public function reject(Request $request, string $changeRequest)
    {
        $cr = ClientPortalChangeRequest::where('client_user_id', Auth::id())->findOrFail($changeRequest);

        if ($cr->status !== 'responded') {
            return back()->withErrors(['error' => 'This request is not awaiting a decision.']);
        }

        $data = $request->validate([
            'client_note' => 'nullable|string|max:2000',
        ]);

        $cr->update([
            'status'      => 'rejected',
            'client_note' => $data['client_note'] ?? null,
        ]);

        return back()->with('success', 'Change request rejected.');
    }

    public function clarify(Request $request, string $changeRequest)
    {
        $cr = ClientPortalChangeRequest::where('client_user_id', Auth::id())->findOrFail($changeRequest);

        if ($cr->status !== 'responded') {
            return back()->withErrors(['error' => 'This request is not awaiting a decision.']);
        }

        $data = $request->validate([
            'client_note' => 'required|string|max:2000',
        ]);

        $cr->update([
            'status'      => 'clarification_requested',
            'client_note' => $data['client_note'],
        ]);

        return back()->with('success', 'Question sent.');
    }
}
