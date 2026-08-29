<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\BlogSeo;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::with(['category', 'seo'])->latest()->get();
        $totalPosts = $posts->count();
        $counts = [
            'all' => Blog::count(),
            'views' => Blog::where('status', 'published')->sum('views'),
            'published' => Blog::where('status', 'published')->count(),
            'draft' => Blog::where('status', 'draft')->count(),
            'scheduled' => Blog::where('status', 'scheduled')->count(),
        ];
        return view('blogs.index', compact('posts', 'totalPosts', 'counts'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:blogs,slug',
            'content'        => 'required|string|min:10',
            'category_id'    => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'og_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'meta_title'     => 'nullable|max:70',
            'meta_description' => 'nullable|max:170',
            'focus_keyword'  => 'nullable|string|max:255',
            'status'         => 'nullable|in:draft,published,scheduled,pending',
        ]);

        try {
            DB::beginTransaction();

            // CHECK: draft exists or not
            if ($request->filled('draft_id')) {
                $blog = Blog::find($request->draft_id);

                if (!$blog) {
                    return back()->with('error', 'Draft not found');
                }
            } else {
                $blog = new Blog();
            }

            // Upload image
            $featuredImage = $blog->featured_image;

            if ($request->hasFile('featured_image')) {
                $upload = $imageService->uploadToPublic($request->file('featured_image'));
                $featuredImage = $upload['path'];
            }

            // Status logic — same resolution as update(), so every Draft/Publish
            // button (top bar or sidebar, create or edit) behaves consistently.
            $action = $request->input('action');
            $status = match (true) {
                $action === 'publish' => 'published',
                $action === 'draft'   => 'draft',
                default               => $request->input('status', 'draft'),
            };
            $publishedAt = $status === 'published' ? now() : null;

            // SAVE (create OR update)
            $blog->fill([
                'title' => $request->title,
                'slug' => Str::slug($request->slug ?: $request->title),
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'featured_image' => $featuredImage,
                'image_alt' => $request->image_alt,
                'image_title' => $request->image_title,
                'image_caption' => $request->image_caption,
                'meta_title' => $request->meta_title ?? $request->title,
                'meta_description' => $request->meta_description ?? $request->excerpt,
                'focus_keyword' => $request->focus_keyword,
                'category_id' => $request->category_id,
                'status' => $status,
                'visibility' => $request->visibility ?? 'public',
                'post_password' => $request->post_password,
                'allow_comments' => $request->boolean('allow_comments', true),
                'published_at' => $publishedAt,
                'reading_time' => $request->filled('reading_time')
                    ? (int) $request->reading_time
                    : $this->calculateReadingTime($request->content),
            ]);

            $blog->save();

            // TAGS
            $this->syncTags($blog, $request->tags);

            // SEO
            BlogSeo::updateOrCreate(
                ['blog_id' => $blog->id],
                [
                    'og_title'            => $request->og_title,
                    'og_description'      => $request->og_description,
                    'canonical_url'       => $request->canonical_url,
                    'robots'              => $request->robots ?? 'index, follow',
                    'schema_type'         => $request->schema_type ?? 'Article',
                    'schema_author'       => $request->schema_author,
                    'schema_rating_value' => $request->schema_rating_value,
                    'schema_rating_count' => $request->schema_rating_count,
                    'meta_keywords'       => $request->meta_keywords,
                    'twitter_card'        => $request->twitter_card ?? 'summary_large_image',
                    'twitter_creator'     => $request->twitter_creator,
                    'hreflang'            => $request->hreflang ?? 'en',
                    'sitemap_priority'    => $request->sitemap_priority ?? '0.9',
                    'sitemap_changefreq'  => $request->sitemap_changefreq ?? 'daily',
                    'custom_head_scripts' => $request->custom_head_scripts,
                ]
            );

            // OG Image (separate upload)
            if ($request->hasFile('og_image')) {
                $ogUpload = $imageService->uploadToPublic($request->file('og_image'));
                $blog->seo()->update(['og_image' => $ogUpload['path']]);
            }

            DB::commit();

            return redirect()->route('blogs.index')
            ->with([
                'toast' => [
                    'type' => 'success',
                    'message' => 'Blog saved successfully'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Store Error:', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function autosave(Request $request)
    {
        try {
            // Prevent empty autosave
            if (!$request->filled('title') && !$request->filled('content')) {
                return response()->json(['skip' => true]);
            }
            DB::beginTransaction();
            // DETERMINE MODE
            $isEdit = !empty($request->draft_id);

            if ($isEdit) {
                // UPDATE EXISTING BLOG
                $blog = Blog::find($request->draft_id);
                if (!$blog) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Blog not found'
                    ], 404);
                }
                $blog->update([
                    'title'            => $request->title ?? $blog->title,
                    'content'          => $request->content ?? $blog->content,
                    'excerpt'          => $request->excerpt,
                    'featured_image' => $request->featured_image ?? $blog->featured_image,
                    'meta_title'       => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'focus_keyword'    => $request->focus_keyword,
                    'category_id'      => $request->category_id,
                    'status'           => 'draft',
                    'reading_time'     => $this->calculateReadingTime($request->content),
                ]);
            } else {
                // CREATE NEW DRAFT (ONLY ONCE)
                $blog = Blog::create([
                    'title'        => $request->title ?? 'Untitled Draft',
                    'content'      => $request->content ?? '',
                    'status'       => 'draft',
                    'author_id'    => auth()->id(),
                    'reading_time' => $this->calculateReadingTime($request->content),
                ]);
            }
            // SAVE SEO
            BlogSeo::updateOrCreate(
                ['blog_id' => $blog->id],
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
            // TAGS
            if ($request->filled('tags')) {
                $tagIds = [];
                foreach (explode(',', $request->tags) as $tagName) {
                    $tagName = trim($tagName);
                    if (!$tagName) continue;
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
                $blog->tags()->sync($tagIds);
            }
            DB::commit();
            return response()->json([
                'success'  => true,
                'draft_id' => $blog->id
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Autosave Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Autosave failed'
            ], 500);
        }
    }


    public function uploadImage(Request $request, ImageUploadService $service)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:5120'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with([
                'toast' => [
                    'type' => 'error',
                    'message' => $validator->errors()->first()
                ]
            ]);
        }
        $upload = $service->uploadToPublic($request->file('image'));

        // Save ONLY if blog exists
        if ($request->draft_id) {
            $blog = Blog::find($request->draft_id);

            if ($blog) {
                // Optional: delete old image
                if ($blog->featured_image) {
                    $this->deleteUploadedImage($blog->featured_image);
                }

                $blog->update([
                    'featured_image' => $upload['path']
                ]);
            }
        }

        return response()->json([
            'path' => $upload['path'],
            'url'  => $upload['url']
        ]);
    }

    /**
     * Delete a previously-uploaded blog image from disk.
     *
     * ImageUploadService::uploadToPublic() writes files straight into
     * public/<folder> via $file->move(), NOT into storage/app/public — so
     * Storage::disk('public')->delete() (which resolves against
     * storage/app/public) was silently a no-op here: it always returned
     * false without deleting anything, since the file never existed at that
     * path. Old featured images were never actually removed on replace/
     * remove, they just piled up in public/blog_images. Delete from the
     * same place the file was actually written instead.
     */
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

    private function calculateReadingTime($content){
        $wordCount = str_word_count(strip_tags($content));
        return ceil($wordCount / 200);
    }

    public function create(){
        $categories = Category::all();
        return view('blogs.create', compact('categories'));
    }

    /**
     * Handle the "+ Add New" category button in the blog editor sidebar.
     * Previously that button only added a fake <option value="new_...">
     * client-side with nothing persisted behind it, so saving the post
     * with the new category selected always failed the
     * `category_id => nullable|exists:categories,id` validation rule.
     */
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        return response()->json([
            'id'   => $category->id,
            'name' => $category->name,
        ]);
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogs.edit', compact('blog'));
    }
    /* ─────────────────────────────────────────────────────────
       UPDATE  (final submit from edit page)
    ───────────────────────────────────────────────────────── */
    
    public function update(Request $request, $id, ImageUploadService $imageService)
    {
        try {
            $blog = Blog::findOrFail($id);

            Validator::make($request->all(), [
                'title'          => 'required|string|max:255',
                'slug'           => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
                'content'        => 'required|string|min:10',
                'category_id'    => 'nullable|exists:categories,id',
                'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
                'og_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
                'meta_title'     => 'nullable|max:70',
                'meta_description' => 'nullable|max:170',
                'focus_keyword'  => 'nullable|string|max:255',
                'status'         => 'nullable|in:draft,published,scheduled,pending',
                'published_at'   => 'nullable|date',
            ])->validate();

            DB::beginTransaction();

            // ── Featured image ──────────────────────────────
            $featuredImage = $blog->featured_image; // keep existing by default

            // User wants to remove the image
            if ($request->input('remove_featured_image') == '1') {
                if ($blog->featured_image) {
                    $this->deleteUploadedImage($blog->featured_image);
                }
                $featuredImage = null;
            }

            // New file uploaded
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($blog->featured_image) {
                    $this->deleteUploadedImage($blog->featured_image);
                }
                $upload        = $imageService->uploadToPublic($request->file('featured_image'));
                $featuredImage = $upload['path'];
            }

            // ── Status / publish date ───────────────────────
            // Same resolution as store(), so every Draft/Publish button (top
            // bar or sidebar) behaves consistently regardless of page.
            $action = $request->input('action');
            $status = match (true) {
                $action === 'publish' => 'published',
                $action === 'draft'   => 'draft',
                default               => $request->input('status', $blog->status),
            };
            $publishedAt = $blog->published_at; // keep existing

            if ($status === 'published' && !$blog->published_at) {
                $publishedAt = now();
            } elseif ($status === 'scheduled' && $request->published_at) {
                $publishedAt = Carbon::parse($request->published_at);
                if ($publishedAt->lte(now())) {
                    $status      = 'published';
                    $publishedAt = now();
                }
            }

            // ── Core blog update ────────────────────────────
            $blog->update([
                'title'            => $request->title,
                'slug'             => Str::slug($request->slug),
                'content'          => $request->content,
                'excerpt'          => $request->excerpt,
                'featured_image'   => $featuredImage,
                'image_alt'        => $request->image_alt,
                'image_title'      => $request->image_title,
                'image_caption'    => $request->image_caption,
                'meta_title'       => $request->meta_title ?? $request->title,
                'meta_description' => $request->meta_description ?? $request->excerpt,
                'focus_keyword'    => $request->focus_keyword,
                'category_id'      => $request->category_id,
                'status'           => $status,
                'published_at'     => $publishedAt,
                'visibility'       => $request->visibility ?? $blog->visibility ?? 'public',
                'post_password'    => $request->post_password,
                'allow_comments'   => $request->boolean('allow_comments', true),
                'reading_time'     => $request->filled('reading_time')
                    ? (int) $request->reading_time
                    : $this->calculateReadingTime($request->content),
            ]);

            // ── Tags ────────────────────────────────────────
            $this->syncTags($blog, $request->tags);

            // ── SEO ─────────────────────────────────────────
            BlogSeo::updateOrCreate(
                ['blog_id' => $blog->id],
                [
                    'og_title'              => $request->og_title,
                    'og_description'        => $request->og_description,
                    'canonical_url'         => $request->canonical_url,
                    'robots'                => $request->robots ?? 'index, follow',
                    'schema_type'           => $request->schema_type ?? 'Article',
                    'schema_author'         => $request->schema_author,
                    'schema_rating_value'   => $request->schema_rating_value,
                    'schema_rating_count'   => $request->schema_rating_count,
                    'meta_keywords'         => $request->meta_keywords,
                    'twitter_card'          => $request->twitter_card ?? 'summary_large_image',
                    'twitter_creator'       => $request->twitter_creator,
                    'hreflang'              => $request->hreflang ?? 'en',
                    'sitemap_priority'      => $request->sitemap_priority ?? '0.9',
                    'sitemap_changefreq'    => $request->sitemap_changefreq ?? 'daily',
                    'custom_head_scripts'   => $request->custom_head_scripts,
                ]
            );

            // ── OG Image (separate upload) ──────────────────
            if ($request->hasFile('og_image')) {
                $ogUpload = $imageService->uploadToPublic($request->file('og_image'));
                $blog->seo()->update(['og_image' => $ogUpload['path']]);
            }

            DB::commit();

            return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Update Error:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function syncTags(Blog $blog, ?string $tagString): void
    {
        if (is_null($tagString) || $tagString === '') {
            $blog->tags()->sync([]);
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
        $blog->tags()->sync($tagIds);
    }
    

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $blog = Blog::findOrFail($id);
            $blog->delete();
            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully'
            ]);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ID'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }


}
