<?php

namespace App\Http\Controllers\TokenAuth;

use App\Http\Controllers\RestfulController;
use App\Http\Requests;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends RestfulController
{

    protected $nameInputParams = ['email', 'password'];

    protected $rules = [
        'email'    => 'required|email|max:255',
        'password' => 'required',
    ];

    /**
     * Create token-based authentication by user information login request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws TokenInvalidException
     * @throws \App\Exceptions\ValidationException
     */
    public function authenticate(Request $request)
    {
        $input = $this->getInput($request);
        $this->validator();

        if (!$token = JWTAuth::attempt($input))
        {
            throw new TokenInvalidException('NOT authorized access');
        }

        JWTAuth::getToken();

        return response()->json(compact('token'));
    }

}
