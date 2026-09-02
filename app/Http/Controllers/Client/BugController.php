<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientPortalBug;
use App\Models\ClientPortalProject;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BugController extends Controller
{
    public function index()
    {
        $bugs = ClientPortalBug::where('client_user_id', Auth::id())
            ->with('project')
            ->latest()
            ->get();

        return view('client.bugs.index', compact('bugs'));
    }

    public function create()
    {
        $projects = ClientPortalProject::where('client_user_id', Auth::id())->get();

        return view('client.bugs.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_portal_project_id' => 'required|integer',
            'title'                    => 'required|string|max:255',
            'description'              => 'required|string|max:5000',
            'steps_to_reproduce'       => 'nullable|string|max:5000',
            'expected_result'          => 'nullable|string|max:2000',
            'actual_result'            => 'nullable|string|max:2000',
            'device'                   => 'nullable|string|max:100',
            'browser'                  => 'nullable|string|max:100',
            'priority'                 => 'required|in:low,medium,high,critical',
            'attachment'               => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480',
        ]);

        // Re-check ownership server-side — never trust the submitted project id.
        $project = ClientPortalProject::where('id', $data['client_portal_project_id'])
            ->where('client_user_id', Auth::id())
            ->firstOrFail();

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $upload = app(ImageUploadService::class)->uploadToPublic($request->file('attachment'), 'client_bugs');
            $attachmentPath = $upload['path'];
        }

        DB::beginTransaction();
        try {
            ClientPortalBug::create([
                'client_portal_project_id' => $project->id,
                'client_user_id'           => Auth::id(),
                'title'                    => $data['title'],
                'description'              => $data['description'],
                'steps_to_reproduce'       => $data['steps_to_reproduce'] ?? null,
                'expected_result'          => $data['expected_result'] ?? null,
                'actual_result'            => $data['actual_result'] ?? null,
                'attachment_path'          => $attachmentPath,
                'device'                   => $data['device'] ?? null,
                'browser'                  => $data['browser'] ?? null,
                'priority'                 => $data['priority'],
                'status'                   => 'reported',
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Client BugController@store failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Could not submit bug report.'])->withInput();
        }

        return redirect()->route('client.bugs.index')->with('success', 'Bug reported.');
    }

    public function show(string $bug)
    {
        $bug = ClientPortalBug::where('client_user_id', Auth::id())
            ->with('project')
            ->findOrFail($bug);

        return view('client.bugs.show', compact('bug'));
    }
}
