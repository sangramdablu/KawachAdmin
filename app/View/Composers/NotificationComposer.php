<?php

namespace App\View\Composers;

use Illuminate\View\View;

/**
 * Shares the current user's notification state with any view that needs it,
 * so the bell badge (navbar) and the dropdown list (master layout) read from
 * one query instead of each fetching it independently.
 */
class NotificationComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        $notifications = $user ? $user->notifications()->latest()->take(10)->get() : collect();
        $unreadCount = $user ? $user->unreadNotifications()->count() : 0;

        $view->with([
            'notifications' => $notifications,
            'unreadNotifCount' => $unreadCount,
        ]);
    }
}
