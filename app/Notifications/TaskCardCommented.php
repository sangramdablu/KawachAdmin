<?php

namespace App\Notifications;

use App\Models\TaskCard;
use App\Models\TaskCardComment;
use Illuminate\Support\Str;

class TaskCardCommented extends BaseNotification
{
    public function __construct(
        protected TaskCard $card,
        protected TaskCardComment $comment,
    ) {
    }

    public function toPayload(object $notifiable): array
    {
        return [
            'title' => $this->comment->user->name . ' commented on "' . $this->card->title . '"',
            'message' => Str::limit($this->comment->body, 100),
            'url' => route('tasks.index', ['card' => $this->card->id]),
            'icon' => 'fas fa-comment-dots',
            'color' => '#2196f3',
        ];
    }
}
