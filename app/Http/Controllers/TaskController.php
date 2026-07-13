<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskList;
use App\Models\TaskCard;
use App\Models\TaskCardComment;
use App\Models\User;
use App\Notifications\TaskCardCommented;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index()
    {
        $lists = TaskList::ordered()
            ->with(['cards' => fn ($q) => $q->ordered()->with('assignee:id,name')->withCount('comments')])
            ->get();

        $users = User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'client'))
            ->orderBy('name')
            ->get(['id', 'name']);

        $can = [
            'create'        => auth()->user()->can('tasks.create'),
            'edit'          => auth()->user()->can('tasks.edit'),
            'delete'        => auth()->user()->can('tasks.delete'),
            'manageColumns' => auth()->user()->can('tasks.manage-columns'),
        ];

        return view('tasks.index', compact('lists', 'users', 'can'));
    }

    public function storeList(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $position = (int) TaskList::max('position') + 1;

            $list = TaskList::create([
                'name'     => $data['name'],
                'position' => $position,
            ]);

            DB::commit();

            return response()->json($list, 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create task list: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to create column'], 500);
        }
    }

    public function updateList(Request $request, TaskList $taskList)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $taskList->update(['name' => $data['name']]);

            DB::commit();

            return response()->json($taskList);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update task list: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to update column'], 500);
        }
    }

    public function reorderLists(Request $request)
    {
        $data = $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:task_lists,id',
        ]);

        try {
            DB::beginTransaction();

            foreach ($data['order'] as $index => $id) {
                TaskList::where('id', $id)->update(['position' => $index]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to reorder task lists: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to reorder columns'], 500);
        }
    }

    public function destroyList(TaskList $taskList)
    {
        try {
            DB::beginTransaction();

            $taskList->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete task list: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to delete column'], 500);
        }
    }

    public function storeCard(Request $request)
    {
        $data = $request->validate([
            'task_list_id' => 'required|exists:task_lists,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'assignee_id'  => 'nullable|exists:users,id',
            'due_date'     => 'nullable|date',
            'priority'     => 'required|in:low,medium,high',
        ]);

        try {
            DB::beginTransaction();

            $position = (int) TaskCard::where('task_list_id', $data['task_list_id'])->max('position') + 1;

            $card = TaskCard::create($data + ['position' => $position]);
            $card->load('assignee:id,name')->loadCount('comments');

            DB::commit();

            return response()->json($this->formatCard($card), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create task card: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to create card'], 500);
        }
    }

    public function updateCard(Request $request, TaskCard $card)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
            'priority'    => 'required|in:low,medium,high',
        ]);

        try {
            DB::beginTransaction();

            $card->update($data);
            $card->load('assignee:id,name')->loadCount('comments');

            DB::commit();

            return response()->json($this->formatCard($card));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update task card: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to update card'], 500);
        }
    }

    public function moveCard(Request $request, TaskCard $card)
    {
        $data = $request->validate([
            'task_list_id' => 'required|exists:task_lists,id',
            'card_ids'     => 'required|array',
            'card_ids.*'   => 'integer|exists:task_cards,id',
        ]);

        try {
            DB::beginTransaction();

            $card->update(['task_list_id' => $data['task_list_id']]);

            foreach ($data['card_ids'] as $index => $id) {
                TaskCard::where('id', $id)->update(['position' => $index]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to move task card: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to move card'], 500);
        }
    }

    public function destroyCard(TaskCard $card)
    {
        try {
            DB::beginTransaction();

            $card->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete task card: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to delete card'], 500);
        }
    }

    public function getComments(TaskCard $card)
    {
        $comments = $card->comments()->with('user:id,name')->get()->map(fn ($c) => [
            'id' => $c->id,
            'body' => $c->body,
            'created_at' => $c->created_at->toIso8601String(),
            'user' => ['id' => $c->user->id, 'name' => $c->user->name],
            'can_delete' => auth()->id() === $c->user_id || auth()->user()->can('tasks.delete'),
        ]);

        return response()->json($comments);
    }

    public function storeComment(Request $request, TaskCard $card)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            $comment = TaskCardComment::create([
                'task_card_id' => $card->id,
                'user_id' => auth()->id(),
                'body' => $data['body'],
            ]);
            $comment->load('user:id,name');

            DB::commit();

            try {
                $this->notifyCommentParticipants($card, $comment);
            } catch (\Throwable $e) {
                Log::error('Failed to notify comment participants: ' . $e->getMessage());
            }

            return response()->json([
                'id' => $comment->id,
                'body' => $comment->body,
                'created_at' => $comment->created_at->toIso8601String(),
                'user' => ['id' => $comment->user->id, 'name' => $comment->user->name],
                'can_delete' => true,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to post comment: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to post comment'], 500);
        }
    }

    public function destroyComment(TaskCardComment $comment)
    {
        if (auth()->id() !== $comment->user_id && !auth()->user()->can('tasks.delete')) {
            return response()->json(['message' => 'You cannot delete this comment'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

    private function notifyCommentParticipants(TaskCard $card, TaskCardComment $comment): void
    {
        $participantIds = $card->comments()->pluck('user_id')
            ->push($card->assignee_id)
            ->filter()
            ->unique()
            ->reject(fn ($id) => (int) $id === (int) $comment->user_id)
            ->values();

        if ($participantIds->isEmpty()) {
            return;
        }

        $recipients = User::whereIn('id', $participantIds)->get();

        Notification::send($recipients, new TaskCardCommented($card, $comment));
    }

    private function formatCard(TaskCard $card): array
    {
        return [
            'id'             => $card->id,
            'task_list_id'   => $card->task_list_id,
            'title'          => $card->title,
            'description'    => $card->description,
            'assignee_id'    => $card->assignee_id,
            'due_date'       => $card->due_date?->format('Y-m-d'),
            'priority'       => $card->priority,
            'assignee'       => $card->assignee ? ['id' => $card->assignee->id, 'name' => $card->assignee->name] : null,
            'comments_count' => $card->comments_count ?? 0,
        ];
    }
}
