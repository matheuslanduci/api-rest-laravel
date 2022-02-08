<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ];

        $this->validate($request, $rules);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        Auth::login($user);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function signIn(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $this->validate($request, $rules);

        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // For some reason, the intelephense does not recognize the createToken() method
        $token = Auth::user()->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }
}
