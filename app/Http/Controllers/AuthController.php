<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $req = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        //user create
        $user = User::create([
            'name' => $req['name'],
            'email' => $req['email'],
            'password' => bcrypt($req['password']),
        ]);

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        //Return response user & token
        return response([
            'user' => $user,
            'token' => $tokenResult,
        ], 200);
    }

    public function login(Request $request)
    {
        $req = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($req)) {
            return response([
                'message' => 'invalid credential'
            ], 400);
        }

        $user = Auth::user();
        $tokenResult = $user->createToken('authToken')->plainTextToken;

        //Return response user & token
        return response([
            'user' => $user,
            'token' => $tokenResult,
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens->each->delete();
        return response([
            'message' => 'logout success'
        ], 200);
    }

    public function user()
    {
        return response([
            'user' => Auth::user()
        ], 200);
    }
}
