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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:blogs,slug',
                'content' => 'required|string|min:50',
                'category_id' => 'nullable|exists:categories,id',
                'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
                'meta_title' => 'nullable|max:70',
                'meta_description' => 'nullable|max:170',
                'focus_keyword' => 'nullable|string|max:255',
                'status' => 'required|in:draft,published,scheduled',
                'published_at' => 'nullable|date',
            ]);

            DB::beginTransaction();

            $featuredImage = null;

            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('portal_assets/blogs');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $featuredImage = 'portal_assets/blogs/' . $filename;
            }

            $status = $request->status;
            $publishedAt = null;

            if ($status === 'published') {
                $publishedAt = now();
            }

            if ($status === 'scheduled') {
                $publishedAt = Carbon::parse($request->published_at);
                if ($publishedAt <= now()) {
                    $status = 'published';
                    $publishedAt = now();
                }
            }

            $blog = Blog::create([
                'title' => $request->title,
                'slug' => Str::slug($request->slug),
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'featured_image' => $featuredImage,
                'meta_title' => $request->meta_title ?? $request->title,
                'meta_description' => $request->meta_description ?? $request->excerpt,
                'focus_keyword' => $request->focus_keyword,
                'category_id' => $request->category_id,
                'status' => $request->status,
                'published_at' => $request->published_at,
                'reading_time' => $this->calculateReadingTime($request->content),
            ]);

            if ($request->tags) {
                $tagIds = [];
                foreach (explode(',', $request->tags) as $tagName) {
                    $tagName = trim($tagName);
                    if (!$tagName) continue;
                    $tag = Tag::firstOrCreate(['slug' => Str::slug($tagName)], ['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $blog->tags()->sync($tagIds);
            }

            BlogSeo::create([
                'blog_id' => $blog->id,
                'og_title' => $request->og_title,
                'og_description' => $request->og_description,
                'canonical_url' => $request->canonical_url,
                'robots' => $request->robots,
                'schema_type' => $request->schema_type,
            ]);

            DB::commit();

            return redirect()->route('blogs.index')->with('success', 'Blog created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Store Error:', ['message' => $e->getMessage(), 'file' => $e->getFile(),'line' => $e->getLine(),]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function autosave(Request $request)
    {
        // Prevent empty save
        if (!$request->title && !$request->content) {
            return response()->json(['skip' => true]);
        }

        $slug = $request->slug ?: \Str::slug($request->title ?? 'draft') . '-' . uniqid();

        $blog = Blog::updateOrCreate(
            ['id' => $request->draft_id],
            [
                'title' => $request->title ?? 'Untitled Draft',
                'slug' => $slug,
                'content' => $request->content ?? '',
                'excerpt' => $request->excerpt,
                'status' => 'draft',
                'user_id' => auth()->id(),
            ]
        );

        return response()->json([
            'success' => true,
            'draft_id' => $blog->id
        ]);
    }
    private function calculateReadingTime($content){
        $wordCount = str_word_count(strip_tags($content));
        return ceil($wordCount / 200);
    }
    public function create(){
        $categories = Category::all();
        return view('blogs.create', compact('categories'));
    }

    public function edit($id, $slug)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->slug !== $slug) {
            return redirect()->route('blogs.edit', [$blog->id, $blog->slug]);
        }
        return view('blogs.edit', compact('blog'));
    }
    public function update(Request $request, Blog $blog)
    {
        $request->validate([    
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Blog updated');
    }
}
