<?php

namespace App\Http\Controllers;

use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Self-service "My Profile" — every authenticated user, regardless of role,
 * can view their own data and change their own avatar here. Unlike
 * RoleAccessController (which lets an admin edit *someone else's* user
 * record via a {user} route parameter), every method on this controller
 * operates on auth()->user() only — there is no route parameter to spoof.
 * Routes are gated with 'auth' alone (see routes/web.php), never
 * 'admin'/'check-permission', so a client-role user can reach this too.
 */
class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $user->loadMissing('roles');

        return view('profile.show', compact('user'));
    }

    /**
     * POST /profile/avatar — always targets auth()->user(). Mirrors
     * RoleAccessController::updateAvatar()'s validation and its use of
     * ImageUploadService::uploadToPublic() for consistency, but is a
     * distinct endpoint: it never accepts/trusts a user id from the
     * request, so it cannot be used to change another user's avatar.
     */
    public function updateAvatar(Request $request, ImageUploadService $imageService): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $user = auth()->user();

        $upload = $imageService->uploadToPublic($request->file('avatar'), 'avatars');
        $user->update(['avatar' => $upload['path']]);

        return response()->json([
            'success'   => true,
            'avatarUrl' => $upload['url'],
        ]);
    }
}
