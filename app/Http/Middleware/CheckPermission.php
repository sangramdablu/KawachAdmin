<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckPermission middleware
 *
 * Usage in routes:
 *   ->middleware('check-permission:pages.edit')
 *   ->middleware('check-permission:users.invite')
 *
 * Super-admin bypasses all permission checks automatically
 * because Spatie gives them all permissions via the wildcard.
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        if (!auth()->user()->can($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "You do not have the '{$permission}' permission.",
                ], 403);
            }
            abort(403, "Permission denied: {$permission}");
        }

        return $next($request);
    }
}