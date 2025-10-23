<?php

namespace Webkul\ApiResources\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Webkul\User\Models\Admin;
use Webkul\ApiResources\Auth\{LoginResource, TokenResource, TokenData};

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            $user = Auth::guard('admin')->user();
            $token = $user->createToken($credentials['device_name'])->plainTextToken;

            $role = $user->role;
            return response()->json([
                'message' => 'Logged in successfully.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                    'role' => [
                        'id' => $role->id,
                        'name' => $role->name,
                        'description' => $role->description,
                        'permission_type' => $role->permission_type,
                        'permission' => $role->permission,
                        'created_at' => $role->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $role->updated_at->format('Y-m-d H:i:s')
                    ],
                    'token' => $token,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s')
                ]
            ], 200);
        }

        throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Invalid Email or Password');
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        dd(__LINE__);
        return response()->json($request->user());
    }
}
