<?php

namespace App\Http\Controllers\TokenAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use JWTAuth;

class AuthController extends Controller
{

    /**
     * Create token-based authentication by user information login request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials))
        {
            return response()->json(['error' => 'NOT authorized access.'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        JWTAuth::getToken();

        return response()->json(compact('token'));
    }

}
