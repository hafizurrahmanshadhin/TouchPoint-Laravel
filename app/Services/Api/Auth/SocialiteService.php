<?php

namespace App\Services\Api\Auth;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialiteService {
    /**
     * Handle socialite login.
     *
     * @param string $provider
     * @param string $token
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function loginWithSocialite(string $provider, string $token): array {
        if (!in_array($provider, ['google', 'apple'])) {
            throw new UnauthorizedHttpException('', 'Provider not supported');
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($token);
        } catch (Exception $e) {
            throw new UnauthorizedHttpException('', 'Invalid token or provider');
        }

        if (!$socialUser || !$socialUser->getEmail()) {
            throw new UnauthorizedHttpException('', 'Invalid social user data');
        }

        // Extract details
        $firstName  = $socialUser->user['given_name'] ?? null;
        $lastName   = $socialUser->user['family_name'] ?? null;
        $avatar     = $socialUser->getAvatar() ?? null;
        $providerId = $socialUser->getId();

        $googleId = $provider === 'google' ? $providerId : null;
        $appleId  = $provider === 'apple' ? $providerId : null;

        // Create or find user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'avatar'            => $avatar,
                'google_id'         => $googleId,
                'apple_id'          => $appleId,
                'password'          => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        // Log user in and generate JWT
        Auth::login($user);
        $jwtToken = JWTAuth::fromUser($user);

        $isNewUser = $user->wasRecentlyCreated;

        return [
            'status'     => true,
            'message'    => $isNewUser ? 'User registered successfully' : 'User logged in successfully',
            'code'       => 200,
            'token_type' => 'bearer',
            'token'      => $jwtToken,
            'data'       => [
                'id'     => $user->id,
                'name'   => $user->first_name . ' ' . $user->last_name,
                'email'  => $user->email,
                'avatar' => $user->avatar,
            ],
        ];
    }
}
