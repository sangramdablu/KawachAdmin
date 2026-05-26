<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole middleware
 *
 * Usage in routes:
 *   ->middleware('check-role:super-admin')
 *   ->middleware('check-role:super-admin,admin')
 *   ->middleware('check-role:editor,admin,super-admin')
 *
 * This wraps Spatie's role check with a clean JSON/redirect response.
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        if (!auth()->user()->hasRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to perform this action.',
                ], 403);
            }
            abort(403, 'Unauthorized — insufficient role.');
        }

        return $next($request);
    }
}