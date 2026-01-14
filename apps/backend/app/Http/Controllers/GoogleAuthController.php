<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // WHITELIST CHECK
            $allowedEmails = explode(',', config('auth.allowed_emails', ''));
            $allowedEmails = array_map('trim', $allowedEmails);

            if (!in_array($googleUser->getEmail(), $allowedEmails)) {
                abort(403, 'Email not authorized. Only whitelisted emails can access this application. Contact administrator.');
            }

            // Create or get user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt('oauth_user_' . str()->random(32)), // Filament needs password
                ]
            );

            Auth::login($user, true); // remember = true

            return redirect()->intended('/admin');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
