<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends RestfulController
{

//    public function __construct()
//    {
//        $this->middleware('jwt.auth');
//    }

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

        return response()->json($user, HttpResponse::HTTP_OK);
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
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|max:255',
            'lastName'  => 'required|max:255',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:6',
        ]);
        if ($validator->fails())
        {
            return response()->json(['code' => HttpResponse::HTTP_BAD_REQUEST, "message" => $validator->messages()], HttpResponse::HTTP_BAD_REQUEST);
        }

        $user            = new User;
        $user->firstName = $request->input('firstName');
        $user->lastName  = $request->input('lastName');
        $user->email     = $request->input('email');
        $user->password  = bcrypt($request->input['password']);
        if ($user->save())
        {
            return response()->json(["result" => 'success'], HttpResponse::HTTP_OK);
        }
    }

    /**
     * Display user information.
     *
     * @param $id User ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user)
        {
            return response()->json(['code' => HttpResponse::HTTP_NOT_FOUND, "message" => "User not exist"], HttpResponse::HTTP_NOT_FOUND);
        }

        return response()->json($user);
    }

    /**
     * Update user in database.
     *
     * @param Request $request
     * @param         $id User ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user)
        {
            return response()->json(['code' => HttpResponse::HTTP_NOT_FOUND, "message" => "User not exist"], HttpResponse::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'max:255',
            'last_name'  => 'max:255',
            'email'      => 'email|max:255|unique:users',
            'password'   => 'min:6',
        ]);
        if ($validator->fails())
        {
            return response()->json(['code' => HttpResponse::HTTP_BAD_REQUEST, "message" => $validator->messages()], HttpResponse::HTTP_BAD_REQUEST);
        }

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
            return response()->json(["result" => 'success'], HttpResponse::HTTP_OK);
        }
    }

    /**
     * Remove user from database.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (empty($user))
        {
            return response()->json(['code' => HttpResponse::HTTP_NOT_FOUND, "message" => "User not exist"], HttpResponse::HTTP_NOT_FOUND);
        }

        if ($user->delete())
        {
            return response()->json(["result" => 'success'], HttpResponse::HTTP_OK);
        }
    }
}
