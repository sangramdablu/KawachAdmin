<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    // Show login
    public function loginForm()
    {
        return view('auth.login');
    }

    // Login

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {

            $invitation = UserInvitation::where('email', $request->email)
                ->whereNotNull('accepted_at')
                ->first();
            if ($invitation) {
                $user = User::create([
                    'name' => trim(
                        $invitation->first_name . ' ' . $invitation->last_name
                    ),
                    'email'    => $invitation->email,
                    'password' => Hash::make($request->password),
                ]);
                $user->assignRole($invitation->role_name);
            }
        }

        if (Auth::attempt(['email'    => $request->email, 'password' => $request->password,])) {
            if (Auth::user()->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been deactivated. Please contact an administrator.',
                ]);
            }

            $request->session()->regenerate();

            if (Auth::user()->hasRole('client')) {
                return redirect()->route('client.portal');
            }

            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    // Show register
    public function registerForm()
    {
        return view('auth.register');
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Logout
    // public function logout(Request $request)
    // {
    //     // Revoke API token if using Sanctum
    //     if ($request->user() && $request->user()->currentAccessToken()) {
    //         $request->user()->currentAccessToken()->delete();
    //     }
    //     // Logout user
    //     Auth::logout();
    //     // Flush all session data
    //     $request->session()->flush();
    //     // Invalidate session
    //     $request->session()->invalidate();
    //     // Regenerate CSRF token
    //     $request->session()->regenerateToken();
    //     return redirect('/login');
    // }
   
    /**
     * Logout authenticated user safely.
     */
    public function logout(Request $request)
    {
        try {
            // Store user info before logout (optional for logs)
            $user = Auth::user();
            // Revoke Sanctum token if exists
            if ($user && method_exists($user, 'currentAccessToken')) {
                $token = $user->currentAccessToken();
                if ($token) {
                    $token->delete();
                }
            }
            // Optional activity log
            if ($user) {
                Log::info('User logged out', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'ip'      => $request->ip(),
                ]);
            }
            // Logout from web guard
            Auth::guard('web')->logout();
            // Invalidate current session
            $request->session()->invalidate();
            // Regenerate CSRF token
            $request->session()->regenerateToken();
            // Clear session data completely
            $request->session()->flush();
            // Clear remember me cookie
            cookie()->queue(cookie()->forget(Auth::getRecallerName()));
            return redirect()->route('login')->with('success', 'You have been logged out successfully.');

        } catch (Throwable $e) {
            Log::error('Logout failed', ['error' => $e->getMessage(), 'ip'    => $request->ip(),]);
            return redirect()->back()
                ->withErrors([
                    'logout' => 'Failed to logout. Please try again.'
                ]);
        }
    }

}
