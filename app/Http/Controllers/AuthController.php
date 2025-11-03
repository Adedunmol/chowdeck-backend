<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Hash;

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
}
