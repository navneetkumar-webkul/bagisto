<?php

namespace Webkul\ApiResources\Http\Controllers\GraphQL;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Webkul\User\Models\Admin;

class AuthGraphQLController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if (!Auth::guard('admin')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('admin')->user();
        $token = $user->createToken($credentials['device_name'])->plainTextToken;

        return response()->json([
            'data' => [
                'login' => [
                    'message' => 'Logged in successfully',
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('sanctum')->currentAccessToken()->delete();

        return response()->json([
            'data' => [
                'logout' => [
                    'message' => 'Logged out successfully'
                ]
            ]
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user('sanctum');

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]
        ]);
    }
}
