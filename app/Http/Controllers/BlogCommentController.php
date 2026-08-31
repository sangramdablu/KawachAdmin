<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Global comment moderation queue — a WordPress "Comments" screen, not
 * nested per-post. Lists every comment across every blog post, newest
 * first, with a status filter (All / Pending / Approved / Rejected).
 *
 * Counts shown here (and anywhere else in the admin) always come from a
 * live COUNT() query against blog_comments / blog_likes — there is no
 * cached/denormalized counter column to drift out of sync.
 */
class BlogCommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = BlogComment::with('blog:id,title,slug')->latest();

        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $comments = $query->paginate(20)->withQueryString();

        $counts = [
            'all'      => BlogComment::count(),
            'pending'  => BlogComment::where('status', 'pending')->count(),
            'approved' => BlogComment::where('status', 'approved')->count(),
            'rejected' => BlogComment::where('status', 'rejected')->count(),
        ];

        return view('blog-comments.index', compact('comments', 'counts', 'status'));
    }

    public function approve(BlogComment $comment)
    {
        try {
            $comment->update(['status' => 'approved']);

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Comment approved']);
            }

            return back()->with('success', 'Comment approved.');
        } catch (\Throwable $e) {
            Log::error('BlogComment Approve Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Could not approve comment.');
        }
    }

    public function reject(BlogComment $comment)
    {
        try {
            $comment->update(['status' => 'rejected']);

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Comment rejected']);
            }

            return back()->with('success', 'Comment rejected.');
        } catch (\Throwable $e) {
            Log::error('BlogComment Reject Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Could not reject comment.');
        }
    }

    public function destroy(BlogComment $comment)
    {
        try {
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully',
            ]);
        } catch (\Throwable $e) {
            Log::error('BlogComment Destroy Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
