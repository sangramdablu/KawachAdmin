<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /**
     * GET /register?token={rawToken}
     */
    public function accept(Request $request): View|RedirectResponse
    {
        $rawToken = $request->query('token');

        if (empty($rawToken)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Invalid or missing invitation link.']);
        }

        $invitation = $this->findValidInvitation($rawToken);

        if (! $invitation) {
            return view('auth.invitation-invalid', [
                'reason' => $this->resolveInvalidReason($rawToken),
            ]);
        }

        if (User::where('email', $invitation->email)->exists()) {
            return redirect()->route('login')
                ->with('info', 'An account for this email already exists. Please log in.');
        }

        return view('auth.accept-invitation', [
            'invitation' => $invitation,
            'token'      => $rawToken,
        ]);
    }

    /**
     * POST /register/invitation
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token'    => ['required', 'string'],
            'name'     => ['required', 'string', 'max:100'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, a number, and a special character.',
        ]);

        // Re-verify token on POST — never rely solely on the hidden form field
        $invitation = $this->findValidInvitation($validated['token']);

        if (! $invitation) {
            return redirect()->route('login')
                ->withErrors(['email' => 'This invitation is no longer valid. Please request a new one.']);
        }

        if (User::where('email', $invitation->email)->exists()) {
            return redirect()->route('login')
                ->with('info', 'An account for this email already exists. Please log in.');
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $invitation->email,   // from DB — not from form
                'password' => Hash::make($validated['password']),
                'status'   => 'active',
            ]);

            $user->assignRole($invitation->role_name);

            $invitation->update(['accepted_at' => now()]);

            Log::info('Invited user registered.', [
                'user_id'       => $user->id,
                'email'         => $user->email,
                'role'          => $invitation->role_name,
                'invitation_id' => $invitation->id,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('InvitationController@register failed.', [
                'email' => $invitation->email,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['general' => 'Something went wrong. Please try again or contact support.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to Kawach Technology, ' . $user->name . '! Your account is ready.');
    }

    // ── Private helpers ──────────────────────────────────────────

    /**
     * Find a valid invitation by the RAW token.
     *
     * The token column stores the raw Str::random(64) value — NOT a hash.
     * UserInvitation::generate() stores it raw, so we query it raw here.
     * Both sides must use the same strategy — change one, change both.
     */
    private function findValidInvitation(string $rawToken): ?UserInvitation
    {
        return UserInvitation::where('token', $rawToken)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();
    }

    private function resolveInvalidReason(string $rawToken): string
    {
        $invitation = UserInvitation::where('token', $rawToken)->first();

        if (! $invitation)            return 'not_found';
        if ($invitation->isAccepted()) return 'already_accepted';
        if ($invitation->isExpired())  return 'expired';

        return 'invalid';
    }
}