<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ]);

        $user = User::create($validated); // laravel hash password automatically
        // Hash::make($validated['password']); // for hashing password

        $token = $user->createToken('auth_token')->plainTextToken; // for generating token

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ], 200); // 200 is the default status code
    }
}
