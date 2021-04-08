<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $inputs = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'document' => 'required|string|unique:users,document'
        ]);


        if($inputs->Fails()){
            return response()->json([
                'status' => 'Error',
                'data' => $inputs->messages()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'document' => $request->document
        ]);

        return response()->json([
            'status' => 'Success',
            'data' => ['token' => $user->createToken('API Token')->plainTextToken]
        ], 200);
    }

    /**
     * Login User via Token API, Revoke olds Token and Create a New
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
                'data' => $inputs->messages()
            ], 401);
        }

        if (!Auth::attempt($request->all())) {
            return response()->json([
                'status' => 'Error',
                'data' => 'Credentials not match'
            ], 401);

        }

        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'Success',
            'data' => ['token' => auth()->user()->createToken('API Token')->plainTextToken]
        ], 200);

    }

    /**
     * Revoke All Tokens
     *
     * @return string[] Token Revoked
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
