<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends RestfulController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = (empty($request->input('limit'))) ? 5 : $request->input('limit');
        $user = User::paginate($limit);
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {

        // $auth = Auth::onceBasic();
        // if ($auth)
        // {
        //     return response()->json(['code' => 401, "message" => "NOT authorized access."], 401);
        // }

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|max:255',
            'lastName'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:6',
        ]);
        if ($validator->fails())
        {
            return response()->json(['code' => 400, "message" => $validator->messages()], 400);
        }

        $user = new User;
        $user->firstName = $request->input('firstName');
        $user->lastName  = $request->input('lastName');
        $user->email      = $request->input('email');
        $user->password   = bcrypt($request->input['password']);
        if ($user->save())
        {
            return response()->json(["result" => 'success'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
       $user = User::find($id);
       if (!$user)
        {
            return response()->json(['code' => 404, "message" => "User not exist"], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $auth = Auth::onceBasic();
        if ($auth)
        {
            return response()->json(['code' => 401, "message" => "NOT authorized access."], 401);
        }
        $user = User::find($id);
        if (!$user)
        {
            return response()->json(['code' => 404, "message" => "User not exist"], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'max:255',
            'last_name'  => 'max:255',
            'email'      => 'email|max:255|unique:users',
            'password'   => 'min:6',
        ]);
        if ($validator->fails())
        {
            return response()->json(['code' => 400, "message" => $validator->messages()], 400);
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
            return response()->json(["result" => 'success'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        // $auth = Auth::onceBasic();
        // if ($auth)
        // {
        //     return response()->json(['code' => 401, "message" => "NOT authorized access."], 401);
        // }

        $user = User::find($id);
        if (empty($user))
        {
            return response()->json(['code' => 404, "message" => "User not exist"], 404);
        }

        if ($user->delete())
        {
            return response()->json(["result" => 'success'], 200);
        }
    }
}
