<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Services\Api\Auth\SocialiteService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialiteController extends Controller {

    // protected SocialiteService $socialiteService;
    // private Helper $helper;

    // public function __construct(SocialiteService $socialiteService, Helper $helper) {
    //     $this->socialiteService = $socialiteService;
    //     $this->helper           = $helper;
    // }

    // /**
    //  * Handle socialite login.
    //  */
    // public function socialiteLogin(Request $request): JsonResponse {
    //     $request->validate([
    //         'token'    => 'required|string',
    //         'provider' => 'required|string|in:google,facebook,apple',
    //     ]);

    //     try {
    //         $token    = $request->input('token');
    //         $provider = $request->input('provider');
    //         $response = $this->socialiteService->loginWithSocialite($provider, $token);

    //         return $this->helper->jsonResponse(
    //             true,
    //             $response['message'],
    //             $response['code'],
    //             $response['data']
    //         );
    //     } catch (UnauthorizedHttpException $e) {
    //         return $this->helper->jsonResponse(false, 'Unauthorized', 401, null, ['error' => $e->getMessage()]);
    //     } catch (Exception $e) {
    //         Log::error('Socialite Login Error: ' . $e->getMessage());
    //         return $this->helper->jsonResponse(false, 'Something went wrong', 500, null, ['error' => $e->getMessage()]);
    //     }
    // }



    // public function SocialLogin(Request $request)
    // {
    //     $request->validate([
    //         'token'    => 'required',
    //         'provider' => 'required|in:google,apple',
    //     ]);

    //     try {
            
    //         $provider   = $request->provider;
    //         // $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);
    //         $socialUser = Socialite::driver($provider)->stateless()->userFromToken($provider);


    //         if ($socialUser) {
    //             $user = User::where('email', $socialUser->email)
    //                 ->orWhere('provider_id', $socialUser->getId())
    //                 ->first();
    //             $isNewUser = false;

    //             if (! $user) {
    //                 $password = Str::random(16);
    //                 $user     = User::create([
    //                     'name'              => $socialUser->getName() ?? $socialUser->getNickname(),
    //                     'email'             => $socialUser->getEmail(),
    //                     'password'          => bcrypt($password),
    //                     'provider'          => $provider,
    //                     'provider_id'       => $socialUser->getId(),
    //                     'role'              => 'user',
    //                     'email_verified_at' => now(),
    //                 ]);
    //                 $isNewUser = true;
    //             }

    //             // Generate token
    //             $token = auth('api')->login($user);

    //             // Prepare success response
    //             $success = [
    //                 'id'    => $user->id,
    //                 'email' => $user->email,
    //                 'role'  => $user->role,
    //                 'selected_pet'  => $user->selected_pet,
    //             ];

    //             // Evaluate the message based on the $isNewUser condition
    //             $message = $isNewUser ? 'User registered and logged in successfully' : 'User logged in successfully';

    //             // Call sendResponse from BaseController and pass the token
             
                
    //         return response()->json([
    //             'status'     => true,
    //             'message'    => 'User registered and logged in successfully.',
    //             'data'       => $message,

    //         ], 201);

    //         } else {
    //            return response()->json([
    //             'status'     => false,
    //             'message'    => 'User registered not and logged in successfully.',

    //            ]);
    //         }
    //     } catch (\Exception $e) {
    //         return Helper::jsonResponse(
    //             false,
    //             'Something went wrong',
    //             500,
    //             ['error' => $e->getMessage()]
    //         );
    //     }
    // }


    public function SocialLogin(Request $request){
        $request->validate([
            'token'    => 'required',
            'provider' => 'required|in:google,apple',
        ]);
    
        try {
            $provider   = $request->provider;
            $accessToken = $request->token;
    
            // Correct: get user from access token
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($accessToken);
    
            if ($socialUser) {
                $user = User::where('email', $socialUser->getEmail())
                            ->orWhere('provider_id', $socialUser->getId())
                            ->first();
    
                $isNewUser = false;
    
                if (! $user) {
                    $password = Str::random(16);
                    $user = User::create([
                        'name'              => $socialUser->getName() ?? $socialUser->getNickname(),
                        'email'             => $socialUser->getEmail(),
                        'password'          => bcrypt($password),
                        'provider'          => $provider,
                        'provider_id'       => $socialUser->getId(),
                        'role'              => 'user',
                        'email_verified_at' => now(),
                    ]);
                    $isNewUser = true;
                }
    
                // Generate JWT or passport token
                $token = auth('api')->login($user);
    
                $data = [
                    'id'            => $user->id,
                    'email'         => $user->email,
                    'role'          => $user->role,
                    'token'         => $token, // Include token in response
                ];
    
                return response()->json([
                    'status'  => true,
                    'message' => $isNewUser ? 'User registered and logged in successfully.' : 'User logged in successfully.',
                    'data'    => $data,
                ], 200);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Could not authenticate with ' . $provider,
                ], 401);
            }
    
        } catch (\Exception $e) {
            Log::error('Social login error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());
            return Helper::jsonResponse(
                true,
                'user logout successfuly',
                200,
                []
            );
        } catch (\Exception $e) {
            return Helper::jsonResponse(
                true,
                'user logout not successfuly',
                200,
                []
            );;
        }
    }

    public function getProfile()
    {
        $user = Auth::user();
        return Helper::jsonResponse(
            true,
            'User profile',
            200,
            $user
        );;
       
    }


}
