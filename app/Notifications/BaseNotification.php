<?php

namespace App\Notifications;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Common base for every notification type the app raises (comments today,
 * future events like contact-form submissions later). Every subclass only
 * has to implement toPayload() — the navbar bell renders any notification
 * that follows this {title, message, url, icon, color} shape without
 * needing to know what kind of event produced it.
 */
abstract class BaseNotification extends Notification implements ShouldBroadcastNow
{
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    abstract public function toPayload(object $notifiable): array;

    public function toDatabase(object $notifiable): array
    {
        return $this->toPayload($notifiable);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        // $this->id is assigned by Laravel before dispatch — include it so the
        // frontend can mark this specific notification as read.
        //
        // The broadcast is always wrapped in Illuminate\Notifications\Events\
        // BroadcastNotificationCreated, which implements ShouldBroadcast (not
        // ShouldBroadcastNow) regardless of this class's own interface — so
        // without forcing the "sync" connection here it would queue onto the
        // default queue connection and wait for a worker, defeating the point
        // of a "real-time" push.
        return (new BroadcastMessage(['id' => $this->id] + $this->toPayload($notifiable)))
            ->onConnection('sync');
    }
}
