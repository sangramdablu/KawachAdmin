<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Page;
use App\Models\PageCategory;
use App\Models\PageService;
use App\Models\PageCaseStudy;
use App\Models\PageTeamMember;
use App\Models\PageTestimonial;
use App\Models\PageFaq;
use App\Models\PagePortfolio;
use App\Models\PageBlog;
use App\Models\PageLandingPage;
use App\Services\ImageUploadService;
use Illuminate\Validation\ValidationException;

/**
 * ============================================================
 *  PageController  — v4 (fully optimized + logged)
 *
 *  Key changes:
 *   - store()  → redirects to pages.index with success toast
 *   - update() → redirects to pages.index with success toast
 *   - index()  → returns JSON when request expects JSON (AJAX)
 *   - Comprehensive Log::info/debug/error throughout
 *   - buildCounts() uses 2 queries instead of 5
 *   - formatPageForJson() gives frontend exactly what it needs
 * ============================================================
 */
class PageController extends Controller
{
    private const PAGE_TYPES = [
        'service', 'casestudy', 'team', 'testimonial',
        'faq', 'portfolio', 'blog', 'landing',
    ];

    private const IMAGE_FOLDERS = [
        'service'     => 'page_images/service',
        'casestudy'   => 'page_images/case_study',
        'team'        => 'page_images/team',
        'testimonial' => 'page_images/testimonial',
        'faq'         => 'page_images/faq',
        'portfolio'   => 'page_images/portfolio',
        'blog'        => 'page_images/blog',
        'landing'     => 'page_images/landing',
    ];

    private const PER_PAGE = 15;

    /* ════════════════════════════════════════════════
       INDEX  (HTML view OR JSON for AJAX)
    ════════════════════════════════════════════════ */
    public function index(Request $request)
    {
        Log::info('PageController@index', [
            'user_id' => auth()->id(),
            'filters' => $request->only(['type','status','search','page','sort','category','featured']),
            'is_ajax' => $request->expectsJson(),
        ]);

        $query = Page::query()
            ->with(['category:id,name'])
            ->select([
                'id','page_type','title','slug','status','is_featured','sort_order',
                'category_id','featured_image','image_alt','meta_description',
                'focus_keyword','author_id','published_at','created_at','updated_at',
            ]);

        // ── Filters ─────────────────────────────────────────
        if ($type = $request->input('type')) {
            abort_if(!in_array($type, self::PAGE_TYPES), 400, 'Invalid page type');
            $query->where('page_type', $type);
            Log::debug('PageController@index filter: type', ['type' => $type]);
        }

        if (($status = $request->input('status')) && $status !== 'all') {
            $query->where('status', $status);
            Log::debug('PageController@index filter: status', ['status' => $status]);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%");
            });
            Log::debug('PageController@index filter: search', ['search' => $search]);
        }

        if ($cat = $request->input('category')) {
            $query->where('category_id', $cat);
        }

        if ($request->filled('featured') && $request->input('featured') !== '') {
            $query->where('is_featured', (bool) $request->input('featured'));
        }

        // ── Sort ────────────────────────────────────────────
        match ($request->input('sort', 'newest')) {
            'oldest'     => $query->oldest('created_at'),
            'title_asc'  => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            'published'  => $query->orderByRaw("CASE WHEN status='published' THEN 0 ELSE 1 END")->latest('published_at'),
            'featured'   => $query->orderByDesc('is_featured')->latest('created_at'),
            default      => $query->latest('created_at'),
        };

        $counts = $this->buildCounts();
        $pages  = $query->paginate(self::PER_PAGE)->withQueryString();

        Log::info('PageController@index result', [
            'total'        => $pages->total(),
            'current_page' => $pages->currentPage(),
        ]);

        // ── JSON for AJAX ────────────────────────────────────
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pages'   => $pages->through(fn($p) => $this->formatPageForJson($p)),
                'counts'  => $counts,
                'meta'    => [
                    'current_page' => $pages->currentPage(),
                    'last_page'    => $pages->lastPage(),
                    'total'        => $pages->total(),
                    'per_page'     => $pages->perPage(),
                    'from'         => $pages->firstItem(),
                    'to'           => $pages->lastItem(),
                ],
            ]);
        }

        // ── HTML view ────────────────────────────────────────
        $categories = PageCategory::orderBy('name')->get(['id','name','page_type']);
        return view('pages.index', compact('pages','counts','categories'));
    }

    /* ════════════════════════════════════════════════
       CREATE
    ════════════════════════════════════════════════ */
    public function create()
    {
        Log::info('PageController@create opened', ['user_id' => auth()->id()]);
        $categories = PageCategory::orderBy('name')->get();
        return view('pages.create', compact('categories'));
    }

    /* ════════════════════════════════════════════════
       STORE CATEGORY (AJAX)
    ════════════════════════════════════════════════ */
    public function storeCategory(Request $request): JsonResponse
    {
        Log::info('PageController@storeCategory attempt', [
            'user_id'   => auth()->id(),
            'name'      => $request->input('name'),
            'page_type' => $request->input('page_type'),
        ]);

        $request->validate([
            'name'      => 'required|string|max:100',
            'page_type' => ['required', Rule::in(self::PAGE_TYPES)],
        ]);

        $slug = Str::slug($request->name);

        if (PageCategory::where('slug', $slug)->where(function ($q) use ($request) {
            $q->where('page_type', $request->page_type)->orWhereNull('page_type');
        })->exists()) {
            Log::warning('PageController@storeCategory: duplicate', ['slug' => $slug]);
            return response()->json(['message' => 'A category with this name already exists.'], 422);
        }

        $category = PageCategory::create([
            'name'      => $request->name,
            'slug'      => $slug,
            'page_type' => $request->page_type,
        ]);

        Log::info('PageController@storeCategory: created', ['id' => $category->id]);
        return response()->json(['id' => $category->id, 'name' => $category->name]);
    }

    /* ════════════════════════════════════════════════
       STORE  —  FIX: redirect to index with toast
    ════════════════════════════════════════════════ */
    public function store(Request $request, ImageUploadService $imageService)
    {
        $status  = $request->input('status', 'draft');
        $isDraft = $status === 'draft';

        Log::info('PageController@store attempt', [
            'user_id'   => auth()->id(),
            'page_type' => $request->input('page_type'),
            'title'     => $request->input('title'),
            'status'    => $status,
            'has_image' => $request->hasFile('featured_image'),
        ]);

        $fellBackToDraft = false;

        if ($isDraft) {
            // ── DRAFT: zero validation, save whatever was filled ──
            $validated = $this->prepareDraftFields($request);
        } else {
            try {
                $validated = $this->validatePage($request);
            } catch (ValidationException $e) {
                Log::warning('PageController@store: publish validation failed, saving as draft instead', [
                    'errors' => $e->errors(),
                ]);
                $validated       = $this->prepareDraftFields($request);
                $fellBackToDraft = true;
            }
        }

        DB::beginTransaction();
        try {
            // Featured image upload runs regardless of draft/publish/validation outcome.
            $imagePath = $this->handleFeaturedImageUpload($request, null, $imageService);
            Log::debug('PageController@store: image handled', ['path' => $imagePath]);

            [$pageStatus, $publishedAt] = $fellBackToDraft
                ? ['draft', null]
                : $this->resolveStatus($validated['status'] ?? 'draft', $validated['published_at'] ?? null);

            $page = Page::create(array_merge(
                $this->coreFields($validated),
                [
                    'featured_image' => $imagePath,
                    'status'         => $pageStatus,
                    'published_at'   => $publishedAt,
                    'author_id'      => auth()->id(),
                ]
            ));

            Log::info('PageController@store: base page created', [
                'page_id' => $page->id, 'page_type' => $page->page_type, 'slug' => $page->slug,
            ]);

            $this->upsertTypeRecord($page, $request, $validated, $imageService);

            DB::commit();

            Log::info('PageController@store: SUCCESS', ['page_id' => $page->id, 'status' => $page->status]);

            if ($fellBackToDraft) {
                return redirect()->route('pages.index')->with('toast', [
                    'type'    => 'warning',
                    'message' => "⚠️ Couldn't publish \"{$page->title}\" — some required fields were missing, so it was saved as a Draft instead.",
                ]);
            }

            return redirect()->route('pages.index')->with('toast', [
                'type'    => 'success',
                'message' => $isDraft
                    ? "💾 \"{$page->title}\" saved as a Draft."
                    : "🎉 {$this->typeLabel($page->page_type)} \"{$page->title}\" created successfully!",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('PageController@store: FAILED', [
                'error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Last-resort fallback: if a publish attempt blew up on the DB layer, retry once as a bare draft.
            if (!$isDraft && !$fellBackToDraft) {
                try {
                    DB::beginTransaction();
                    $draft = $this->prepareDraftFields($request);
                    $imagePath = $this->handleFeaturedImageUpload($request, null, $imageService);

                    $page = Page::create(array_merge(
                        $this->coreFields($draft),
                        ['featured_image' => $imagePath, 'status' => 'draft', 'published_at' => null, 'author_id' => auth()->id()]
                    ));
                    $this->upsertTypeRecord($page, $request, $draft, $imageService);
                    DB::commit();

                    return redirect()->route('pages.index')->with('toast', [
                        'type'    => 'warning',
                        'message' => "⚠️ Something went wrong publishing — saved as a Draft instead so your work isn't lost.",
                    ]);
                } catch (\Throwable $e2) {
                    DB::rollBack();
                    Log::error('PageController@store: draft fallback also failed', ['error' => $e2->getMessage()]);
                }
            }

            return back()->withInput()->with('toast', [
                'type' => 'error', 'message' => 'Failed to save page: ' . $e->getMessage(),
            ]);
        }
    }

    /* ════════════════════════════════════════════════
       EDIT
    ════════════════════════════════════════════════ */
    public function edit(Page $page)
    {
        Log::info('PageController@edit opened', [
            'user_id'   => auth()->id(),
            'page_id'   => $page->id,
            'page_type' => $page->page_type,
        ]);

        $page->load('category');
        $typeData   = $this->loadTypeData($page);
        $categories = PageCategory::orderBy('name')->get();

        Log::debug('PageController@edit: type data loaded', [
            'page_id'       => $page->id,
            'has_type_data' => !is_null($typeData),
        ]);

        return view('pages.create', compact('page','typeData','categories'));
    }

    /* ════════════════════════════════════════════════
       UPDATE  —  FIX: redirect to index with toast
    ════════════════════════════════════════════════ */
    public function update(Request $request, Page $page, ImageUploadService $imageService)
    {
        $status  = $request->input('status', $page->status);
        $isDraft = $status === 'draft';

        Log::info('PageController@update attempt', [
            'user_id' => auth()->id(), 'page_id' => $page->id,
            'page_type' => $page->page_type, 'title' => $request->input('title'), 'status' => $status,
        ]);

        $validated = $isDraft
            ? $this->prepareDraftFields($request, $page->id)
            : $this->validatePage($request, $page->id);

        Log::debug('PageController@update: proceeding', ['page_id' => $page->id, 'draft_mode' => $isDraft]);

        DB::beginTransaction();
        try {
            $imagePath = $this->handleFeaturedImageUpload($request, $page, $imageService);

            [$pageStatus, $publishedAt] = $this->resolveStatus(
                $validated['status'] ?? $page->status,
                $validated['published_at'] ?? $page->published_at
            );

            $page->update(array_merge(
                $this->coreFields($validated),
                ['featured_image' => $imagePath, 'status' => $pageStatus, 'published_at' => $publishedAt]
            ));

            $this->upsertTypeRecord($page, $request, $validated, $imageService);

            DB::commit();

            return redirect()->route('pages.index')->with('toast', [
                'type'    => 'success',
                'message' => $isDraft
                    ? "💾 \"{$page->title}\" saved as a Draft."
                    : "✅ {$this->typeLabel($page->page_type)} \"{$page->title}\" updated!",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PageController@update: FAILED', [
                'page_id' => $page->id, 'error' => $e->getMessage(),
                'file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->with('toast', [
                'type' => 'error', 'message' => 'Failed to update page: ' . $e->getMessage(),
            ]);
        }
    }

    /* ════════════════════════════════════════════════
       DESTROY (soft-delete, AJAX)
    ════════════════════════════════════════════════ */
    public function destroy(Page $page): JsonResponse
    {
        Log::info('PageController@destroy attempt', [
            'user_id' => auth()->id(),
            'page_id' => $page->id,
            'title'   => $page->title,
        ]);

        try {
            $page->delete();
            Log::info('PageController@destroy: SUCCESS', ['page_id' => $page->id]);
            return response()->json(['success' => true, 'message' => "'{$page->title}' moved to trash."]);
        } catch (\Throwable $e) {
            Log::error('PageController@destroy: FAILED', ['page_id' => $page->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Delete failed.'], 500);
        }
    }

    /* ════════════════════════════════════════════════
       FORCE DELETE
    ════════════════════════════════════════════════ */
    public function forceDelete(int $id): JsonResponse
    {
        Log::info('PageController@forceDelete attempt', ['user_id' => auth()->id(), 'page_id' => $id]);

        $page = Page::withTrashed()->findOrFail($id);
        try {
            if ($page->featured_image) {
                $fullPath = public_path($page->featured_image);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::debug('PageController@forceDelete: image removed', ['path' => $fullPath]);
                }
            }
            $page->forceDelete();
            Log::info('PageController@forceDelete: SUCCESS', ['page_id' => $id]);
            return response()->json(['success' => true, 'message' => "'{$page->title}' permanently deleted."]);
        } catch (\Throwable $e) {
            Log::error('PageController@forceDelete: FAILED', ['page_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Force delete failed.'], 500);
        }
    }

    /* ════════════════════════════════════════════════
       RESTORE
    ════════════════════════════════════════════════ */
    public function restore(int $id): JsonResponse
    {
        Log::info('PageController@restore attempt', ['user_id' => auth()->id(), 'page_id' => $id]);
        $page = Page::withTrashed()->findOrFail($id);
        try {
            $page->restore();
            Log::info('PageController@restore: SUCCESS', ['page_id' => $id]);
            return response()->json(['success' => true, 'message' => "'{$page->title}' restored."]);
        } catch (\Throwable $e) {
            Log::error('PageController@restore: FAILED', ['page_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Restore failed.'], 500);
        }
    }

    /* ════════════════════════════════════════════════
       TRASHED
    ════════════════════════════════════════════════ */
    public function trashed()
    {
        Log::info('PageController@trashed', ['user_id' => auth()->id()]);
        $pages = Page::onlyTrashed()->latest('deleted_at')->paginate(20);
        return view('pages.trashed', compact('pages'));
    }

    /* ════════════════════════════════════════════════
       BULK ACTION (AJAX)
    ════════════════════════════════════════════════ */
    public function bulk(Request $request): JsonResponse
    {
        Log::info('PageController@bulk attempt', [
            'user_id' => auth()->id(),
            'action'  => $request->input('action'),
            'count'   => count($request->input('ids', [])),
        ]);

        $request->validate([
            'action' => ['required', Rule::in(['publish','draft','delete','restore'])],
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:pages,id',
        ]);

        $ids    = $request->ids;
        $action = $request->action;

        try {
            match ($action) {
                'publish' => Page::whereIn('id', $ids)->update(['status' => 'published', 'published_at' => now()]),
                'draft'   => Page::whereIn('id', $ids)->update(['status' => 'draft']),
                'delete'  => Page::whereIn('id', $ids)->delete(),
                'restore' => Page::withTrashed()->whereIn('id', $ids)->restore(),
            };

            Log::info('PageController@bulk: SUCCESS', ['action' => $action, 'count' => count($ids)]);

            return response()->json(['success' => true, 'message' => count($ids) . ' pages updated.']);
        } catch (\Throwable $e) {
            Log::error('PageController@bulk: FAILED', ['action' => $action, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Bulk action failed.'], 500);
        }
    }

    /* ════════════════════════════════════════════════
       TOGGLE STATUS (AJAX)
    ════════════════════════════════════════════════ */
    public function toggleStatus(Page $page): JsonResponse
    {
        $newStatus = $page->status === 'published' ? 'draft' : 'published';

        Log::info('PageController@toggleStatus', [
            'user_id'    => auth()->id(),
            'page_id'    => $page->id,
            'old_status' => $page->status,
            'new_status' => $newStatus,
        ]);

        $page->update([
            'status'       => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : $page->published_at,
        ]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    /* ════════════════════════════════════════════════
       CHECK SLUG (AJAX)
    ════════════════════════════════════════════════ */
    public function checkSlug(Request $request): JsonResponse
    {
        $request->validate(['slug' => 'required|string|max:220']);

        $query = Page::where('slug', $request->slug);
        if ($request->filled('ignore_id')) {
            $query->where('id', '!=', $request->ignore_id);
        }

        $available = !$query->exists();

        Log::debug('PageController@checkSlug', [
            'slug'      => $request->slug,
            'available' => $available,
        ]);

        return response()->json(['available' => $available]);
    }

    /* ════════════════════════════════════════════════
       PRIVATE HELPERS
    ════════════════════════════════════════════════ */

    /**
     * Build stats counts using only 2 DB queries.
     */
    private function buildCounts(): array
    {
        $statusCounts = Page::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $typeCounts = Page::query()
            ->selectRaw('page_type, COUNT(*) as count')
            ->groupBy('page_type')
            ->pluck('count', 'page_type')
            ->toArray();

        return [
            'all'       => array_sum($statusCounts),
            'published' => $statusCounts['published'] ?? 0,
            'draft'     => $statusCounts['draft'] ?? 0,
            'pending'   => $statusCounts['pending'] ?? 0,
            'scheduled' => $statusCounts['scheduled'] ?? 0,
            'by_type'   => array_replace(array_fill_keys(self::PAGE_TYPES, 0), $typeCounts),
        ];
    }

    /**
     * Format a Page model for JSON API response.
     * Gives frontend only what it needs — no passwords, tokens, etc.
     */
    private function formatPageForJson(Page $page): array
    {
        return [
            'id'               => $page->id,
            'page_type'        => $page->page_type,
            'type_label'       => $page->type_label,
            'title'            => $page->title,
            'slug'             => $page->slug,
            'status'           => $page->status,
            'is_featured'      => (bool) $page->is_featured,
            'sort_order'       => $page->sort_order,
            'category'         => $page->category ? ['id' => $page->category->id, 'name' => $page->category->name] : null,
            // Images are in public/ — use asset() path
            'featured_image'   => $page->featured_image ? asset($page->featured_image) : null,
            'image_alt'        => $page->image_alt,
            'meta_description' => $page->meta_description,
            'focus_keyword'    => $page->focus_keyword,
            'published_at'     => $page->published_at?->toISOString(),
            'created_at'       => $page->created_at->toISOString(),
            'updated_at'       => $page->updated_at->toISOString(),
            'edit_url'         => route('pages.edit', $page),
            'view_url'         => url($page->slug),
            'toggle_url'       => route('pages.toggle-status', $page),
            'delete_url'       => route('pages.destroy', $page),
        ];
    }

    /** Central validation — shared + type-specific rules. */
    private function validatePage(Request $request, ?int $ignoreId = null): array
    {
        $type   = $request->input('page_type', 'service');
        $status = $request->input('status', 'draft');

        Log::debug('PageController@validatePage', ['type' => $type, 'status' => $status, 'ignore_id' => $ignoreId]);

        $rules = [
            'page_type'          => ['required', Rule::in(self::PAGE_TYPES)],
            'title'              => 'required|string|max:200',   // title always required, even for drafts
            'slug'               => [
                'required','string','max:220','regex:/^[a-z0-9\-]+$/',
                Rule::unique('pages','slug')->ignore($ignoreId),
            ],
            'status'             => ['required', Rule::in(['draft','pending','published','scheduled'])],
            'visibility'         => ['required', Rule::in(['public','private','password'])],
            'page_password'      => 'nullable|string|max:255',
            'is_featured'        => 'nullable|boolean',
            'sort_order'         => 'nullable|integer|min:0',
            'category_id'        => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($value)) return;
                    $exists = PageCategory::where('id', $value)
                        ->where(function ($q) use ($request) {
                            $q->where('page_type', $request->page_type)->orWhereNull('page_type');
                        })->exists();
                    if (!$exists) $fail('The selected category is not valid for this page type.');
                },
            ],
            'published_at'       => 'nullable|date',
            'featured_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'image_alt'          => 'nullable|string|max:200',
            'image_title'        => 'nullable|string|max:200',
            'focus_keyword'      => 'nullable|string|max:200',
            'meta_title'         => 'nullable|string|max:200',
            'meta_description'   => 'nullable|string|max:500',
            'meta_keywords'      => 'nullable|string|max:500',
            'canonical_url'      => 'nullable|url|max:500',
            'robots'             => ['nullable', Rule::in(['index, follow','noindex, follow','index, nofollow','noindex, nofollow'])],
            'schema_type'        => 'nullable|string|max:50',
            'og_title'           => 'nullable|string|max:200',
            'og_description'     => 'nullable|string|max:500',
            'twitter_card'       => ['nullable', Rule::in(['summary_large_image','summary'])],
            'hreflang'           => 'nullable|string|max:10',
            'sitemap_priority'   => 'nullable|numeric|min:0|max:1',
            'sitemap_changefreq' => ['nullable', Rule::in(['always','hourly','daily','weekly','monthly','yearly','never'])],
            'custom_head'        => 'nullable|string|max:5000',
            'tags'               => 'nullable|string|max:1000',
        ];

        return $request->validate(
            array_merge($rules, $this->typeRules($type, $status)),
            $this->validationMessages()
        );
    }

    /**
     * $status drives strictness:
     *  - draft / pending  → everything content-related is optional; save whatever was filled
     *  - published / scheduled → full required checks apply
     */
    private function typeRules(string $type, string $status = 'draft'): array
    {
        $isDraft = in_array($status, ['draft', 'pending'], true);
        $req     = $isDraft ? 'nullable' : 'required';
        $reqWith = fn(string $withField) => $isDraft ? 'nullable' : "required_with:{$withField}";

        return match ($type) {
            'service' => [
                'short_description'      => 'nullable|string|max:500',
                'content'                => "{$req}|string",
                'features'               => 'nullable|array',
                'features.*.title'       => $reqWith('features') . '|string|max:150',
                'features.*.description' => 'nullable|string|max:500',
                'process_steps'          => 'nullable|array',
                'process_steps.*.title'  => $reqWith('process_steps') . '|string|max:150',
                'price_from'             => 'nullable|string|max:50',
                'billing_cycle'          => ['nullable', Rule::in(['','one-time','monthly','yearly','per-project','hourly'])],
                'cta_url'                => 'nullable|url|max:500',
                'cta_text'               => 'nullable|string|max:100',
                'technologies'           => 'nullable|string|max:500',
            ],
            'casestudy' => [
                'client_name'                 => "{$req}|string|max:150",
                'client_industry'             => 'nullable|string|max:100',
                'business_size'               => 'nullable|string|max:150',
                'location'                    => 'nullable|string|max:150',
                'business_model'              => 'nullable|string|max:150',
                'project_duration'            => 'nullable|string|max:80',
                'completion_date'             => 'nullable|string|max:20',
                'project_url'                 => 'nullable|url|max:500',
                'challenge'                   => "{$req}|string",
                'existing_challenges'         => 'nullable|array',
                'existing_challenges.*.text'  => $reqWith('existing_challenges') . '|string|max:300',
                'solution'                    => "{$req}|string",
                'goals'                       => 'nullable|array',
                'goals.*.title'               => $reqWith('goals') . '|string|max:150',
                'goals.*.desc'                => 'nullable|string|max:400',
                'goals.*.icon'                => 'nullable|string|max:60',
                'goals.*.color'               => 'nullable|string|max:30',
                'solution_modules'            => 'nullable|array',
                'solution_modules.*.name'     => $reqWith('solution_modules') . '|string|max:150',
                'solution_modules.*.desc'     => 'nullable|string|max:400',
                'solution_modules.*.icon'     => 'nullable|string|max:60',
                'kpis'                        => 'nullable|array',
                'kpis.*.label'                => $reqWith('kpis') . '|string|max:100',
                'kpis.*.value'                => $reqWith('kpis') . '|string|max:50',
                'cs_technologies'             => 'nullable|string|max:500',
                'tech_stack'                  => 'nullable|array',
                'tech_stack.*.category'       => $reqWith('tech_stack') . '|string|max:100',
                'tech_stack.*.items'          => 'nullable|string|max:500',
                'cs_process_steps'            => 'nullable|array',
                'cs_process_steps.*.badge'    => 'nullable|string|max:60',
                'cs_process_steps.*.title'    => $reqWith('cs_process_steps') . '|string|max:150',
                'cs_process_steps.*.desc'     => 'nullable|string|max:600',
                'achievements'                => 'nullable|array',
                'achievements.*.title'        => $reqWith('achievements') . '|string|max:150',
                'achievements.*.desc'         => 'nullable|string|max:400',
                'before_after'                => 'nullable|array',
                'before_after.*.before'       => $reqWith('before_after') . '|string|max:200',
                'before_after.*.after'        => $reqWith('before_after') . '|string|max:200',
                'compliance_items'            => 'nullable|array',
                'compliance_items.*.title'    => $reqWith('compliance_items') . '|string|max:120',
                'compliance_items.*.desc'     => 'nullable|string|max:300',
                'compliance_items.*.icon'     => 'nullable|string|max:60',
                'cs_testimonial_quote'        => 'nullable|string|max:2000',
                'cs_testimonial_name'         => 'nullable|string|max:150',
                'cs_testimonial_role'         => 'nullable|string|max:150',
            ],
            'team' => [
                'job_title'       => "{$req}|string|max:150",
                'department'      => 'nullable|string|max:100',
                'member_email'    => 'nullable|email|max:200',
                'member_phone'    => 'nullable|string|max:50',
                'member_location' => 'nullable|string|max:150',
                'bio'             => 'nullable|string',
                'skills'          => 'nullable|array',
                'skills.*.name'   => $reqWith('skills') . '|string|max:80',
                'skills.*.level'  => $reqWith('skills') . '|integer|min:0|max:100',
                'social_linkedin' => 'nullable|url|max:500',
                'social_twitter'  => 'nullable|url|max:500',
                'social_github'   => 'nullable|url|max:500',
                'social_website'  => 'nullable|url|max:500',
            ],
            'testimonial' => [
                'testimonial_quote'    => "{$req}|string|max:3000",
                'testimonial_name'     => "{$req}|string|max:150",
                'testimonial_role'     => 'nullable|string|max:200',
                'testimonial_industry' => 'nullable|string|max:100',
                'testimonial_service'  => 'nullable|string|max:150',
                'testimonial_rating'   => 'nullable|integer|min:1|max:5',
                'testimonial_video'    => 'nullable|url|max:500',
            ],
            'faq' => [
                'faq_category'    => ['nullable', Rule::in(['general','services','pricing','technical','support','billing','legal'])],
                'faq_order'       => 'nullable|integer|min:0',
                'faqs'            => $isDraft ? 'nullable|array' : 'required|array|min:1',
                'faqs.*.question' => "{$req}|string|max:400",
                'faqs.*.answer'   => "{$req}|string|max:5000",
            ],
            'portfolio' => [
                'portfolio_desc'     => 'nullable|string|max:500',
                'portfolio_category' => ['nullable', Rule::in(['web','mobile','design','ai','ecommerce','saas','devops'])],
                'portfolio_year'     => 'nullable|integer|min:2000|max:2099',
                'portfolio_url'      => 'nullable|url|max:500',
                'portfolio_tech'     => 'nullable|string|max:500',
                'portfolio_content'  => 'nullable|string',
            ],
            'blog' => [
                'blog_content' => "{$req}|string",
                'excerpt'      => 'nullable|string|max:500',
            ],
            'landing' => [
                'hero_headline'         => "{$req}|string|max:250",
                'hero_subheadline'      => 'nullable|string|max:350',
                'cta_primary_text'      => 'nullable|string|max:100',
                'cta_primary_url'       => 'nullable|url|max:500',
                'cta_secondary_text'    => 'nullable|string|max:100',
                'cta_secondary_url'     => 'nullable|url|max:500',
                'landing_content'       => 'nullable|string',
                'landing_stats'         => 'nullable|array',
                'landing_stats.*.value' => $reqWith('landing_stats') . '|string|max:50',
                'landing_stats.*.label' => $reqWith('landing_stats') . '|string|max:100',
            ],
            default => [],
        };
    }    

    private function validationMessages(): array
    {
        return [
            'title.required'             => 'Please enter a title for this page.',
            'slug.required'              => 'A URL slug is required.',
            'slug.unique'                => 'This slug is already taken — please choose another.',
            'slug.regex'                 => 'Slug may only contain lowercase letters, numbers and hyphens.',
            'status.required'            => 'Please select a status.',
            'featured_image.image'       => 'The featured image must be a valid image file.',
            'featured_image.max'         => 'Featured image must not exceed 5 MB.',
            'content.required'           => 'Full description is required.',
            'content.min'                => 'Full description must be at least 20 characters.',
            'challenge.required'         => 'Please describe the client challenge.',
            'solution.required'          => 'Please describe the solution provided.',
            'client_name.required'       => 'Client / company name is required.',
            'job_title.required'         => 'Job title is required for team members.',
            'testimonial_quote.required' => 'Testimonial quote is required.',
            'testimonial_name.required'  => 'Client name is required.',
            'faqs.required'              => 'Please add at least one FAQ item.',
            'faqs.min'                   => 'Please add at least one FAQ item.',
            'faqs.*.question.required'   => 'Each FAQ must have a question.',
            'faqs.*.answer.required'     => 'Each FAQ must have an answer.',
            'hero_headline.required'     => 'Hero headline is required for landing pages.',
            'blog_content.required'      => 'Blog content is required.',
            'blog_content.min'           => 'Blog content must be at least 50 characters.',
            'canonical_url.url'          => 'Canonical URL must include https://.',
            'cta_url.url'                => 'CTA URL must be a valid URL.',
            'project_url.url'            => 'Project URL must be a valid URL.',
        ];
    }

    private function coreFields(array $v): array
    {
        return [
            'page_type'          => $v['page_type'],
            'title'              => $v['title'],
            'slug'               => Str::slug($v['slug']),
            'visibility'         => $v['visibility'] ?? 'public',
            'page_password'      => ($v['visibility'] ?? '') === 'password' ? ($v['page_password'] ?? null) : null,
            'is_featured'        => (bool) ($v['is_featured'] ?? false),
            'sort_order'         => (int) ($v['sort_order'] ?? 0),
            'category_id'        => (!empty($v['category_id']) && is_numeric($v['category_id'])) ? (int) $v['category_id'] : null,
            'focus_keyword'      => $v['focus_keyword'] ?? null,
            'meta_title'         => $v['meta_title'] ?? null,
            'meta_description'   => $v['meta_description'] ?? null,
            'meta_keywords'      => $v['meta_keywords'] ?? null,
            'canonical_url'      => $v['canonical_url'] ?? null,
            'robots'             => $v['robots'] ?? 'index, follow',
            'schema_type'        => $v['schema_type'] ?? 'WebPage',
            'og_title'           => $v['og_title'] ?? null,
            'og_description'     => $v['og_description'] ?? null,
            'twitter_card'       => $v['twitter_card'] ?? 'summary_large_image',
            'hreflang'           => $v['hreflang'] ?? 'en',
            'sitemap_priority'   => $v['sitemap_priority'] ?? 0.9,
            'sitemap_changefreq' => $v['sitemap_changefreq'] ?? 'weekly',
            'custom_head_script' => $v['custom_head'] ?? null,
            'tags'               => $v['tags'] ?? null,
            'image_alt'          => $v['image_alt'] ?? null,
            'image_title'        => $v['image_title'] ?? null,
        ];
    }

    private function handleFeaturedImageUpload(Request $request, ?Page $page, ImageUploadService $imageService): ?string
    {
        if (!$request->hasFile('featured_image')) {
            return $page?->featured_image ?? null;
        }

        if ($page?->featured_image) {
            $oldPath = public_path($page->featured_image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
                Log::debug('PageController: old image deleted', ['path' => $oldPath]);
            }
        }

        $folder = self::IMAGE_FOLDERS[$request->input('page_type','service')] ?? 'page_images/general';
        $upload = $imageService->uploadToPublic($request->file('featured_image'), $folder);

        Log::debug('PageController: new image uploaded', ['path' => $upload['path']]);
        return $upload['path'];
    }

    private function resolveStatus(string $status, mixed $publishedAt): array
    {
        if ($status === 'published') {
            return ['published', $publishedAt ?? now()];
        }
        if ($status === 'scheduled' && $publishedAt) {
            $dt = \Carbon\Carbon::parse($publishedAt);
            return $dt->isPast() ? ['published', now()] : ['scheduled', $dt];
        }
        return [$status, null];
    }

    private function upsertTypeRecord(Page $page, Request $request, array $v, ImageUploadService $imageService): void
    {
        Log::debug('PageController@upsertTypeRecord', ['page_id' => $page->id, 'type' => $page->page_type]);

        match ($page->page_type) {
            'service' => PageService::updateOrCreate(['page_id' => $page->id], [
                'short_description' => $v['short_description'] ?? null,
                'content'           => $v['content'] ?? null,
                'features'          => isset($v['features']) ? array_values($v['features']) : null,
                'process_steps'     => isset($v['process_steps']) ? array_values($v['process_steps']) : null,
                'price_from'        => $v['price_from'] ?? null,
                'billing_cycle'     => !empty($v['billing_cycle']) ? $v['billing_cycle'] : null,
                'cta_url'           => $v['cta_url'] ?? null,
                'cta_text'          => $v['cta_text'] ?? null,
                'technologies'      => $v['technologies'] ?? null,
            ]),
            'casestudy' => PageCaseStudy::updateOrCreate(['page_id' => $page->id], [
                'client_name'          => $v['client_name'] ?? null,
                'client_industry'      => $v['client_industry'] ?? null,
                'business_size'        => $v['business_size'] ?? null,
                'location'             => $v['location'] ?? null,
                'business_model'       => $v['business_model'] ?? null,
                'project_duration'     => $v['project_duration'] ?? null,
                'completion_date'      => $v['completion_date'] ?? null,
                'project_url'          => $v['project_url'] ?? null,
                'challenge'            => $v['challenge'] ?? null,
                'existing_challenges'  => isset($v['existing_challenges']) ? array_values($v['existing_challenges']) : null,
                'solution'             => $v['solution'] ?? null,
                'goals'                => isset($v['goals']) ? array_values($v['goals']) : null,
                'solution_modules'     => isset($v['solution_modules']) ? array_values($v['solution_modules']) : null,
                'kpis'                 => isset($v['kpis']) ? array_values($v['kpis']) : null,
                'technologies'         => $v['cs_technologies'] ?? null,
                'tech_stack'           => isset($v['tech_stack']) ? array_values($v['tech_stack']) : null,
                'cs_process_steps'     => isset($v['cs_process_steps']) ? array_values($v['cs_process_steps']) : null,
                'achievements'         => isset($v['achievements']) ? array_values($v['achievements']) : null,
                'before_after'         => isset($v['before_after']) ? array_values($v['before_after']) : null,
                'compliance_items'     => isset($v['compliance_items']) ? array_values($v['compliance_items']) : null,
                'gallery'              => $this->handleCsGalleryUpload($request, $page, $imageService),
                'testimonial_quote'    => $v['cs_testimonial_quote'] ?? null,
                'testimonial_name'     => $v['cs_testimonial_name'] ?? null,
                'testimonial_role'     => $v['cs_testimonial_role'] ?? null,
            ]),
            'team' => PageTeamMember::updateOrCreate(['page_id' => $page->id], [
                'job_title'       => $v['job_title'] ?? null,
                'department'      => $v['department'] ?? null,
                'member_email'    => $v['member_email'] ?? null,
                'member_phone'    => $v['member_phone'] ?? null,
                'member_location' => $v['member_location'] ?? null,
                'bio'             => $v['bio'] ?? null,
                'skills'          => isset($v['skills']) ? array_values($v['skills']) : null,
                'social_linkedin' => $v['social_linkedin'] ?? null,
                'social_twitter'  => $v['social_twitter'] ?? null,
                'social_github'   => $v['social_github'] ?? null,
                'social_website'  => $v['social_website'] ?? null,
            ]),
            'testimonial' => PageTestimonial::updateOrCreate(['page_id' => $page->id], [
                'testimonial_quote'    => $v['testimonial_quote'] ?? null,
                'testimonial_name'     => $v['testimonial_name'] ?? null,
                'testimonial_role'     => $v['testimonial_role'] ?? null,
                'testimonial_industry' => $v['testimonial_industry'] ?? null,
                'testimonial_service'  => $v['testimonial_service'] ?? null,
                'testimonial_rating'   => $v['testimonial_rating'] ?? 5,
                'testimonial_video'    => $v['testimonial_video'] ?? null,
            ]),
            'faq' => PageFaq::updateOrCreate(['page_id' => $page->id], [
                'faq_category' => $v['faq_category'] ?? 'general',
                'faq_order'    => $v['faq_order'] ?? 0,
                'faq_items'    => array_values($v['faqs'] ?? []),
            ]),
            'portfolio' => PagePortfolio::updateOrCreate(['page_id' => $page->id], [
                'portfolio_desc'     => $v['portfolio_desc'] ?? null,
                'portfolio_category' => !empty($v['portfolio_category']) ? $v['portfolio_category'] : null,
                'portfolio_year'     => !empty($v['portfolio_year']) ? (int) $v['portfolio_year'] : null,
                'portfolio_url'      => $v['portfolio_url'] ?? null,
                'portfolio_tech'     => $v['portfolio_tech'] ?? null,
                'portfolio_content'  => $v['portfolio_content'] ?? null,
                'gallery'            => $this->handleGalleryUpload($request, $page, $imageService),
            ]),
            'blog' => PageBlog::updateOrCreate(['page_id' => $page->id], [
                'blog_content' => $v['blog_content'] ?? null,
                'excerpt'      => $v['excerpt'] ?? null,
            ]),
            'landing' => PageLandingPage::updateOrCreate(['page_id' => $page->id], [
                'hero_headline'      => $v['hero_headline'] ?? null,
                'hero_subheadline'   => $v['hero_subheadline'] ?? null,
                'cta_primary_text'   => $v['cta_primary_text'] ?? null,
                'cta_primary_url'    => $v['cta_primary_url'] ?? null,
                'cta_secondary_text' => $v['cta_secondary_text'] ?? null,
                'cta_secondary_url'  => $v['cta_secondary_url'] ?? null,
                'landing_content'    => $v['landing_content'] ?? null,
                'landing_stats'      => isset($v['landing_stats']) ? array_values($v['landing_stats']) : null,
            ]),
        };
    }


    private function handleCsGalleryUpload(Request $request, ?Page $page, ImageUploadService $imageService): ?array
    {
        // The model casts `gallery` to array — no json_decode needed anymore.
        $existing = $page?->caseStudy?->gallery ?? [];

        if (!$request->hasFile('cs_gallery')) {
            return $existing ?: null;
        }
        foreach ($request->file('cs_gallery') as $file) {
            if (!$file || !$file->isValid()) continue;
            $upload = $imageService->uploadToPublic($file, 'page_images/case_study/gallery');
            $existing[] = $upload['path'];
        }
        return $existing;
    }

    private function handleGalleryUpload(Request $request, ?Page $page, ImageUploadService $imageService): ?array
    {
        $existing = $page?->portfolio?->gallery ?? [];

        if (!$request->hasFile('gallery')) {
            return $existing ?: null;
        }
        foreach ($request->file('gallery') as $file) {
            if (!$file || !$file->isValid()) continue;
            $upload     = $imageService->uploadToPublic($file, 'page_images/portfolio/gallery');
            $existing[] = $upload['path'];
        }
        return $existing;
    }

    private function loadTypeData(Page $page): ?object
    {
        return match ($page->page_type) {
            'service'     => $page->service,
            'casestudy'   => $page->caseStudy,
            'team'        => $page->teamMember,
            'testimonial' => $page->testimonial,
            'faq'         => $page->faq,
            'portfolio'   => $page->portfolio,
            'blog'        => $page->blog,
            'landing'     => $page->landingPage,
            default       => null,
        };
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'service'     => 'Service',
            'casestudy'   => 'Case Study',
            'team'        => 'Team Member',
            'testimonial' => 'Testimonial',
            'faq'         => 'FAQ',
            'portfolio'   => 'Portfolio Item',
            'blog'        => 'Blog Post',
            'landing'     => 'Landing Page',
            default       => ucfirst($type),
        };
    }

    /**
     * Draft path — no validation. Pull everything the form sent, as-is.
     * Only guards against things that would literally crash the query
     * (missing title/slug/page_type), and even those are auto-repaired,
     * never rejected.
     */
    private function prepareDraftFields(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->except(['_token', '_method', 'featured_image', 'gallery', 'cs_gallery']);

        // Title: never block on this — just default it.
        $data['title'] = trim((string) ($data['title'] ?? '')) ?: 'Untitled Draft';

        // Slug: auto-generate from title if blank, silently de-duplicate instead of erroring.
        $slug = Str::slug(trim((string) ($data['slug'] ?? '')) ?: $data['title']);
        if ($slug === '') {
            $slug = 'draft-' . Str::random(6);
        }
        $data['slug'] = $this->makeUniqueSlug($slug, $ignoreId);

        // page_type / visibility / status: keep whatever was sent if valid, else safe default.
        $data['page_type']  = in_array($data['page_type'] ?? '', self::PAGE_TYPES, true)
            ? $data['page_type'] : 'service';
        $data['visibility'] = in_array($data['visibility'] ?? '', ['public', 'private', 'password'], true)
            ? $data['visibility'] : 'public';
        $data['status']     = 'draft';

        Log::debug('PageController@prepareDraftFields: bypassing validation for draft', [
            'page_type' => $data['page_type'],
            'slug'      => $data['slug'],
        ]);

        return $data;
    }

    /** Silently de-duplicate a slug instead of throwing a "slug taken" error. */
    private function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $original = $slug;
        $i = 2;
        while (
            Page::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }

}