<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\Api\Auth\SocialiteService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SocialiteController extends Controller {
    protected SocialiteService $socialiteService;
    private Helper $helper;

    public function __construct(SocialiteService $socialiteService, Helper $helper) {
        $this->socialiteService = $socialiteService;
        $this->helper           = $helper;
    }

    /**
     * Handle socialite login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function socialiteLogin(Request $request): JsonResponse {
        $request->validate([
            'token'    => 'required|string',
            'provider' => 'required|string|in:google,apple',
        ]);

        try {
            $token    = $request->input('token');
            $provider = $request->input('provider');
            $response = $this->socialiteService->loginWithSocialite($provider, $token);

            return response()->json([
                'status'     => true,
                'message'    => $response['message'],
                'code'       => $response['code'],
                'token_type' => $response['token_type'],
                'token'      => $response['token'],
                'data'       => $response['data'],
            ], $response['code']);
        } catch (UnauthorizedHttpException $e) {
            return $this->helper->jsonResponse(false, 'Unauthorized', 401, null, ['error' => $e->getMessage()]);
        } catch (Exception $e) {
            return $this->helper->jsonResponse(false, 'Something went wrong', 500, null, ['error' => $e->getMessage()]);
        }
    }
}
