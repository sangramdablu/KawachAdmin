<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobPostingController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::withCount('applications')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        // Flat, JS-friendly copy for populating the edit modal (list fields
        // joined back into newline text to match the textarea inputs).
        $jobsJson = $jobs->map(fn ($job) => [
            'id'                    => $job->id,
            'title'                 => $job->title,
            'department'            => $job->department,
            'location'              => $job->location,
            'countries'             => $job->countries ?? [],
            'type'                  => $job->type,
            'experience_level'      => $job->experience_level,
            'openings'              => $job->openings,
            'salary_range'          => $job->salary_range,
            'application_deadline'  => optional($job->application_deadline)->format('Y-m-d'),
            'summary'               => $job->summary,
            'responsibilities'      => implode("\n", $job->responsibilities ?? []),
            'requirements'          => implode("\n", $job->requirements ?? []),
            'nice_to_have'          => implode("\n", $job->nice_to_have ?? []),
            'status'                => $job->status,
            'sort_order'            => $job->sort_order,
        ]);

        return view('jobs.index', compact('jobs', 'jobsJson'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['created_by'] = auth()->id();

        $job = JobPosting::create($this->parseLists($validated));

        return response()->json([
            'success' => true,
            'message' => "\"{$job->title}\" posted successfully.",
            'job'     => $job,
        ], 201);
    }

    public function update(Request $request, JobPosting $job): JsonResponse
    {
        $validated = $this->validated($request);

        $job->update($this->parseLists($validated));

        return response()->json([
            'success' => true,
            'message' => "\"{$job->title}\" updated successfully.",
            'job'     => $job->fresh(),
        ]);
    }

    public function toggleStatus(JobPosting $job): JsonResponse
    {
        $newStatus = $job->status === 'active' ? 'inactive' : 'active';
        $job->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function destroy(JobPosting $job): JsonResponse
    {
        $title = $job->title;
        $job->delete();

        return response()->json(['success' => true, 'message' => "\"{$title}\" deleted."]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'                 => ['required', 'string', 'max:150'],
            'department'            => ['required', 'string', 'max:100'],
            'location'              => ['required', 'string', 'max:150'],
            'countries'             => ['nullable', 'array'],
            'countries.*'           => ['string', Rule::in(array_keys(JobPosting::COUNTRIES))],
            'type'                  => ['required', 'string', Rule::in(['Full-time', 'Part-time', 'Contract', 'Internship', 'Freelance'])],
            'experience_level'      => ['required', 'string', 'max:50'],
            'openings'              => ['nullable', 'integer', 'min:1', 'max:999'],
            'salary_range'          => ['nullable', 'string', 'max:150'],
            'application_deadline'  => ['nullable', 'date'],
            'summary'               => ['required', 'string', 'max:2000'],
            'responsibilities'      => ['required', 'string'],
            'requirements'          => ['required', 'string'],
            'nice_to_have'          => ['nullable', 'string'],
            'status'                => ['required', Rule::in(['active', 'inactive'])],
            'sort_order'            => ['nullable', 'integer', 'min:0'],
        ]);
    }

    /**
     * The three "list" fields arrive from the form as newline-separated
     * textareas — split them into arrays for the json columns.
     */
    private function parseLists(array $validated): array
    {
        foreach (['responsibilities', 'requirements', 'nice_to_have'] as $field) {
            if (array_key_exists($field, $validated)) {
                $validated[$field] = collect(preg_split('/\r\n|\r|\n/', (string) $validated[$field]))
                    ->map(fn ($line) => trim($line))
                    ->filter()
                    ->values()
                    ->all();
            }
        }

        $validated['openings']   = $validated['openings'] ?? 1;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (JobPosting::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
