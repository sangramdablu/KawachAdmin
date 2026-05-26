<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Only users with 'super-admin' or 'admin' role (via Spatie) can pass.
     * All other authenticated users get a 403.
     * Unauthenticated users are redirected to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in — send to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check Spatie role — hasRole() accepts array or pipe-separated string
        if (!$user->hasRole(['super-admin', 'admin', 'editor', 'viewer', 'content-manager'])) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}