<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\News;
use App\Models\Tag;
use App\Models\NewsSeo;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Validator;

/**
 * Admin Newsroom module — mirrors BlogController's structure/conventions
 * (draft/publish resolution via `action`, ImageUploadService::uploadToPublic()
 * for uploads, Crypt::encrypt()/decrypt() id pattern for delete) so staff who
 * already know the Blog editor get the same UX here. No likes/comments
 * concept exists for News, so those counts are omitted.
 */
class NewsController extends Controller
{
    public function index()
    {
        $posts = News::with(['category', 'seo'])
            ->latest()
            ->get();

        $counts = [
            'all'       => News::count(),
            'views'     => News::where('status', 'published')->sum('views'),
            'published' => News::where('status', 'published')->count(),
            'draft'     => News::where('status', 'draft')->count(),
            'scheduled' => News::where('status', 'scheduled')->count(),
        ];

        return view('news.index', compact('posts', 'counts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('news.create', compact('categories'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'title'                => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255|unique:news,slug',
            'content'              => 'required|string|min:10',
            'category_id'          => 'nullable|exists:categories,id',
            'featured_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'og_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'meta_title'           => 'nullable|max:70',
            'meta_description'     => 'nullable|max:170',
            'focus_keyword'        => 'nullable|string|max:255',
            'status'               => 'nullable|in:draft,published,scheduled',
            'external_source_url'  => 'nullable|url|max:255',
        ]);

        try {
            DB::beginTransaction();

            if ($request->filled('draft_id')) {
                $news = News::find($request->draft_id);
                if (!$news) {
                    return back()->with('error', 'Draft not found');
                }
            } else {
                $news = new News();
            }

            $featuredImage = $news->featured_image;
            if ($request->hasFile('featured_image')) {
                $upload = $imageService->uploadToPublic($request->file('featured_image'), 'news_images');
                $featuredImage = $upload['path'];
            }

            // Same draft/publish resolution as BlogController::store()/update()
            // so every Draft/Publish button behaves consistently.
            $action = $request->input('action');
            $status = match (true) {
                $action === 'publish' => 'published',
                $action === 'draft'   => 'draft',
                default               => $request->input('status', 'draft'),
            };
            $publishedAt = $status === 'published' ? now() : null;

            $news->fill([
                'title'                 => $request->title,
                'slug'                  => Str::slug($request->slug ?: $request->title),
                'content'               => $request->content,
                'excerpt'               => $request->excerpt,
                'featured_image'        => $featuredImage,
                'image_alt'             => $request->image_alt,
                'image_title'           => $request->image_title,
                'image_caption'         => $request->image_caption,
                'meta_title'            => $request->meta_title ?? $request->title,
                'meta_description'      => $request->meta_description ?? $request->excerpt,
                'focus_keyword'         => $request->focus_keyword,
                'category_id'           => $request->category_id,
                'author_id'             => $news->author_id ?? auth()->id(),
                'external_source_name'  => $request->external_source_name,
                'external_source_url'   => $request->external_source_url,
                'status'                => $status,
                'visibility'            => $request->visibility ?? 'public',
                'published_at'          => $publishedAt,
                'reading_time'          => $request->filled('reading_time')
                    ? (int) $request->reading_time
                    : $this->calculateReadingTime($request->content),
            ]);

            $news->save();

            $this->syncTags($news, $request->tags);

            NewsSeo::updateOrCreate(
                ['news_id' => $news->id],
                [
                    'og_title'            => $request->og_title,
                    'og_description'      => $request->og_description,
                    'canonical_url'       => $request->canonical_url,
                    'robots'              => $request->robots ?? 'index, follow',
                    'schema_type'         => $request->schema_type ?? 'NewsArticle',
                    'schema_author'       => $request->schema_author,
                    'schema_rating_value' => $request->schema_rating_value,
                    'schema_rating_count' => $request->schema_rating_count,
                    'meta_keywords'       => $request->meta_keywords,
                    'twitter_card'        => $request->twitter_card ?? 'summary_large_image',
                    'twitter_creator'     => $request->twitter_creator,
                    'hreflang'            => $request->hreflang ?? 'en',
                    'sitemap_priority'    => $request->sitemap_priority ?? '0.8',
                    'sitemap_changefreq'  => $request->sitemap_changefreq ?? 'daily',
                    'custom_head_scripts' => $request->custom_head_scripts,
                ]
            );

            if ($request->hasFile('og_image')) {
                $ogUpload = $imageService->uploadToPublic($request->file('og_image'), 'news_images');
                $news->seo()->update(['og_image' => $ogUpload['path']]);
            }

            DB::commit();

            return redirect()->route('news.index')->with([
                'toast' => [
                    'type' => 'success',
                    'message' => 'News article saved successfully',
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News Store Error:', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function autosave(Request $request)
    {
        try {
            if (!$request->filled('title') && !$request->filled('content')) {
                return response()->json(['skip' => true]);
            }

            DB::beginTransaction();
            $isEdit = !empty($request->draft_id);

            if ($isEdit) {
                $news = News::find($request->draft_id);
                if (!$news) {
                    return response()->json(['error' => true, 'message' => 'News not found'], 404);
                }
                $news->update([
                    'title'            => $request->title ?? $news->title,
                    'content'          => $request->content ?? $news->content,
                    'excerpt'          => $request->excerpt,
                    'featured_image'   => $request->featured_image ?? $news->featured_image,
                    'meta_title'       => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'focus_keyword'    => $request->focus_keyword,
                    'category_id'      => $request->category_id,
                    'status'           => 'draft',
                    'reading_time'     => $this->calculateReadingTime($request->content),
                ]);
            } else {
                $news = News::create([
                    'title'        => $request->title ?? 'Untitled Draft',
                    'slug'         => Str::slug(($request->title ?: 'untitled-draft') . '-' . Str::random(6)),
                    'content'      => $request->content ?? '',
                    'status'       => 'draft',
                    'author_id'    => auth()->id(),
                    'reading_time' => $this->calculateReadingTime($request->content),
                ]);
            }

            NewsSeo::updateOrCreate(
                ['news_id' => $news->id],
                [
                    'og_title'            => $request->og_title,
                    'og_description'      => $request->og_description,
                    'canonical_url'       => $request->canonical_url,
                    'robots'              => $request->robots,
                    'schema_type'         => $request->schema_type,
                    'meta_keywords'       => $request->meta_keywords,
                    'schema_author'       => $request->schema_author,
                    'schema_rating_value' => $request->schema_rating_value,
                    'schema_rating_count' => $request->schema_rating_count,
                    'twitter_card'        => $request->twitter_card,
                    'twitter_creator'     => $request->twitter_creator,
                    'hreflang'            => $request->hreflang,
                    'sitemap_priority'    => $request->sitemap_priority,
                    'sitemap_changefreq'  => $request->sitemap_changefreq,
                    'custom_head_scripts' => $request->custom_head_scripts,
                ]
            );

            if ($request->filled('tags')) {
                $this->syncTags($news, $request->tags);
            }

            DB::commit();
            return response()->json(['success' => true, 'draft_id' => $news->id]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('News Autosave Error', ['message' => $e->getMessage(), 'line' => $e->getLine()]);
            return response()->json(['error' => true, 'message' => 'Autosave failed'], 500);
        }
    }

    public function uploadImage(Request $request, ImageUploadService $service)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with([
                'toast' => ['type' => 'error', 'message' => $validator->errors()->first()],
            ]);
        }

        $upload = $service->uploadToPublic($request->file('image'), 'news_images');

        if ($request->draft_id) {
            $news = News::find($request->draft_id);
            if ($news) {
                if ($news->featured_image) {
                    $this->deleteUploadedImage($news->featured_image);
                }
                $news->update(['featured_image' => $upload['path']]);
            }
        }

        return response()->json(['path' => $upload['path'], 'url' => $upload['url']]);
    }

    /**
     * Handle the "+ Add New" category button in the news editor sidebar —
     * same fix as BlogController::storeCategory(): persists a real category
     * row instead of a client-side-only placeholder option, so the news
     * `category_id => nullable|exists:categories,id` validation never fails.
     */
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        return response()->json(['id' => $category->id, 'name' => $category->name]);
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id, ImageUploadService $imageService)
    {
        try {
            $news = News::findOrFail($id);

            Validator::make($request->all(), [
                'title'                => 'required|string|max:255',
                'slug'                 => 'required|string|max:255|unique:news,slug,' . $news->id,
                'content'              => 'required|string|min:10',
                'category_id'          => 'nullable|exists:categories,id',
                'featured_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
                'og_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
                'meta_title'           => 'nullable|max:70',
                'meta_description'     => 'nullable|max:170',
                'focus_keyword'        => 'nullable|string|max:255',
                'status'               => 'nullable|in:draft,published,scheduled',
                'published_at'         => 'nullable|date',
                'external_source_url'  => 'nullable|url|max:255',
            ])->validate();

            DB::beginTransaction();

            $featuredImage = $news->featured_image;

            if ($request->input('remove_featured_image') == '1') {
                if ($news->featured_image) {
                    $this->deleteUploadedImage($news->featured_image);
                }
                $featuredImage = null;
            }

            if ($request->hasFile('featured_image')) {
                if ($news->featured_image) {
                    $this->deleteUploadedImage($news->featured_image);
                }
                $upload        = $imageService->uploadToPublic($request->file('featured_image'), 'news_images');
                $featuredImage = $upload['path'];
            }

            $action = $request->input('action');
            $status = match (true) {
                $action === 'publish' => 'published',
                $action === 'draft'   => 'draft',
                default               => $request->input('status', $news->status),
            };
            $publishedAt = $news->published_at;

            if ($status === 'published' && !$news->published_at) {
                $publishedAt = now();
            } elseif ($status === 'scheduled' && $request->published_at) {
                $publishedAt = Carbon::parse($request->published_at);
                if ($publishedAt->lte(now())) {
                    $status      = 'published';
                    $publishedAt = now();
                }
            }

            $news->update([
                'title'                => $request->title,
                'slug'                 => Str::slug($request->slug),
                'content'              => $request->content,
                'excerpt'              => $request->excerpt,
                'featured_image'       => $featuredImage,
                'image_alt'            => $request->image_alt,
                'image_title'          => $request->image_title,
                'image_caption'        => $request->image_caption,
                'meta_title'           => $request->meta_title ?? $request->title,
                'meta_description'     => $request->meta_description ?? $request->excerpt,
                'focus_keyword'        => $request->focus_keyword,
                'category_id'          => $request->category_id,
                'external_source_name' => $request->external_source_name,
                'external_source_url'  => $request->external_source_url,
                'status'               => $status,
                'published_at'         => $publishedAt,
                'visibility'           => $request->visibility ?? $news->visibility ?? 'public',
                'reading_time'         => $request->filled('reading_time')
                    ? (int) $request->reading_time
                    : $this->calculateReadingTime($request->content),
            ]);

            $this->syncTags($news, $request->tags);

            NewsSeo::updateOrCreate(
                ['news_id' => $news->id],
                [
                    'og_title'            => $request->og_title,
                    'og_description'      => $request->og_description,
                    'canonical_url'       => $request->canonical_url,
                    'robots'              => $request->robots ?? 'index, follow',
                    'schema_type'         => $request->schema_type ?? 'NewsArticle',
                    'schema_author'       => $request->schema_author,
                    'schema_rating_value' => $request->schema_rating_value,
                    'schema_rating_count' => $request->schema_rating_count,
                    'meta_keywords'       => $request->meta_keywords,
                    'twitter_card'        => $request->twitter_card ?? 'summary_large_image',
                    'twitter_creator'     => $request->twitter_creator,
                    'hreflang'            => $request->hreflang ?? 'en',
                    'sitemap_priority'    => $request->sitemap_priority ?? '0.8',
                    'sitemap_changefreq'  => $request->sitemap_changefreq ?? 'daily',
                    'custom_head_scripts' => $request->custom_head_scripts,
                ]
            );

            if ($request->hasFile('og_image')) {
                $ogUpload = $imageService->uploadToPublic($request->file('og_image'), 'news_images');
                $news->seo()->update(['og_image' => $ogUpload['path']]);
            }

            DB::commit();

            return redirect()->route('news.index')->with('success', 'News article updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News Update Error:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * destroy() decrypts the id (see routes + news/index.blade.php &
     * news/edit.blade.php "Danger Zone" button) — this is the AJAX DELETE
     * pattern BlogController::destroy() uses, built correctly from the
     * start here rather than the plain-<a>-to-a-DELETE-only-route bug that
     * had to be fixed on the Blog delete button this session.
     */
    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $news = News::findOrFail($id);
            $news->delete();
            return response()->json(['success' => true, 'message' => 'News article deleted successfully']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    private function syncTags(News $news, ?string $tagString): void
    {
        if (is_null($tagString) || $tagString === '') {
            $news->tags()->sync([]);
            return;
        }

        $tagIds = [];
        foreach (explode(',', $tagString) as $tagName) {
            $tagName = trim($tagName);
            if (!$tagName) continue;
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }
        $news->tags()->sync($tagIds);
    }

    /** See BlogController::deleteUploadedImage() — same public-path convention. */
    private function deleteUploadedImage(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }
        $fullPath = public_path($relativePath);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content ?? ''));
        return max(1, ceil($wordCount / 200));
    }
}
