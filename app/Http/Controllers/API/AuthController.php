<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Laravel\Lumen\Routing\Controller as Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        if (!$token = JWTAuth::attempt($request->only('email','password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['access_token' => $token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => JWTAuth::refresh(JWTAuth::getToken())
        ]);
    }
}
