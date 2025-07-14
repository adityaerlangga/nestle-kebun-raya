<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Google OAuth provider.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
            ]);

            Auth::login($user);

            // Redirect to Angular frontend dashboard
            return redirect()->away('http://localhost:4200/dashboard');

        } catch (\Exception $e) {
            // Redirect to login page with error
            return redirect()->away('http://localhost:4200/login?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        try {
            // Check if user is authenticated before trying to logout
            if (Auth::check()) {
                Auth::logout();
            }
            
            // Always invalidate and regenerate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            // Even if there's an error, try to clear the session
            try {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } catch (\Exception $sessionError) {
                // Ignore session errors
            }

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }
    }

    /**
     * Get the authenticated user.
     */
    public function user()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
