<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!$token = Auth::attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }

    public function getCurrentUser()
    {
        return response()->json(auth()->user());
    }

    public function refresh()
    {
        $token = Auth::refresh();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->noContent();
    }
}
