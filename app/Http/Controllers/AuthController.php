<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|string|min:3|confirmed',
        // ]);
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ]);
        if($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        try{

            $user = User::create($request->all()); // laravel hash password automatically
            // Hash::make($validated['password']); // for hashing password

            $token = $user->createToken('auth_token')->plainTextToken; // for generating token

            return response()->json([
                'access_token' => $token,
                'user' => $user
            ], 200); // 200 is the default status code

        } catch (\Exception $e) {
            return response()->json([
                // 'message' => 'Something went wrong'
                'error' => $e->getMessage()
            ], 500);
        }
    }
   
}
