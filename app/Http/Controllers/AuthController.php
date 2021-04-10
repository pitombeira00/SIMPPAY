<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'document' => $request->document
        ]);

        Wallet::create([
            'value' => 0,
            'user_id' => $user->id
        ]);

        return response()->json([
            'status' => 'Success',
            'data' => ['token' => $user->createToken('API Token')->plainTextToken],
            'message' => 'User Register with success'
        ], 200);
    }

    /**
     * Login User via Token API, Revoke olds Token and Create a New
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse 'Welcome in SimpPay'
     */
    public function login(Request $request)
    {
        $inputs = Validator::make($request->all(),[
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if($inputs->Fails()){
            return response()->json([
                'status' => 'Error',
                'data' => NULL,
                'message' => $inputs->messages()
            ], 401);
        }

        if (!Auth::attempt($request->all())) {
            return response()->json([
                'status' => 'Error',
                'data' => NULL,
                'message' => 'Credentials not match'
            ], 401);

        }

        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'Success',
            'data' => ['token' => auth()->user()->createToken('API Token')->plainTextToken],
            'message' => 'Welcome in SimpPay'
        ], 200);

    }

    /**
     * Revoke All Tokens
     *
     * @return \Illuminate\Http\JsonResponse Token Revoked
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'Success',
            'data' => NULL,
            'message' =>'Tokens Revoked'
        ], 200);
    }
}
