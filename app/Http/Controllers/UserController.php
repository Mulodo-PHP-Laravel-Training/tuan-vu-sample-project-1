<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class UserController extends RestfulController
{

    protected $nameInputParams = ['firstName', 'lastName', 'email', 'password'];

    protected $rules = [
        'firstName' => 'required|max:255',
        'lastName'  => 'required|max:255',
        'email'     => 'required|email|max:255|unique:users',
        'password'  => 'required|min:6',
    ];

    /**
     * Display a listing of user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = (empty($request->input('limit'))) ? 5 : $request->input('limit');
        $user  = User::paginate($limit);

        return $this->responseApi($user);
    }

    /**
     * Store a newly created user in database.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->getInput($request);
        $this->validator();

        $user            = new User;
        $user->firstName = $request->input('firstName');
        $user->lastName  = $request->input('lastName');
        $user->email     = $request->input('email');
        $user->password  = bcrypt($request->input['password']);
        if ($user->save())
        {
            return $this->responseApi('success');
        }
    }

    /**
     * Display user information.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function show($id)
    {
        $user = User::find($id, ['id', 'firstName', 'lastName', 'email']);
        if (!$user)
        {
            throw new ApiException("User does not exist", HttpResponse::HTTP_NOT_FOUND);
        }

        return $this->responseApi($user);
    }

    /**
     * Update user in database
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     * @throws \App\Exceptions\ValidationException
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user)
        {
            throw new ApiException("User does not exist", HttpResponse::HTTP_NOT_FOUND);
        }

        $this->getInput($request);
        $this->validator();

        $firstName = $request->input('first_name');
        $lastName  = $request->input('last_name');
        $email     = $request->input('email');
        $password  = $request->input('password');

        if (!empty($firstName))
        {
            $user->first_name = $firstName;
        }
        elseif (!empty($lastName))
        {
            $user->last_name = $lastName;
        }
        elseif (!empty($email))
        {
            $user->email = $email;
        }
        elseif (!empty($password))
        {
            $user->password = bcrypt($password);
        }

        if ($user->update())
        {
            return $this->responseApi('success');
        }
    }

    /**
     * Remove user from database
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (empty($user))
        {
            throw new ApiException("User does not exist", HttpResponse::HTTP_NOT_FOUND);
        }

        if ($user->delete())
        {
            return $this->responseApi('success');
        }
    }
}
