<?php

namespace App\Http\Controllers\Web\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller {
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return RedirectResponse
     */
    public function GoogleRedirect(): RedirectResponse {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->with([
                'access_type' => 'offline',
                'prompt'      => 'consent',
            ])
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return JsonResponse
     */
    public function GoogleCallback(): JsonResponse {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            return Helper::jsonResponse(true, 'Token Retrieve Successfully', 200, [
                'access_token'  => $user->token,
                'refresh_token' => $user->refreshToken,
                'expires_in'    => $user->expiresIn,
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
