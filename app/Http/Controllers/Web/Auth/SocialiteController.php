<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller {
    /**
     * Redirects the user to Google's OAuth page for authentication.
     *
     * @return RedirectResponse
     */
    public function GoogleRedirect(): RedirectResponse {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handles the callback after Google has authenticated the user.
     *
     * @return RedirectResponse
     */
    public function GoogleCallback(): RedirectResponse {
        $user = Socialite::driver('google')->user();
        dd($user);
    }
}