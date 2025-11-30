<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request) {
        $validated = $request->validated();

        $user = User::create([...$validated, 'password' => Hash::make($validated['password'])]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'the provided credentials are incorrect.'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'user logged in successfully',
            'token' => $token
        ]);
    }
    public function logout(Request $request) {
       $request->user()->currentAccessToken()->delete();

       return response()->json([
            'status' => 'success',
            'message' => 'user logged out successfully'
        ]);
    }
}
