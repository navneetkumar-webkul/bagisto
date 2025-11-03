<?php

namespace Webkul\ApiResources\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'       => 'required|email',
            'password'    => 'required',
            'device_name' => 'required',
        ]);

        if (! Auth::guard('admin')->attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            return response()->json([
                'error' => trans('api-resources.auth.login.invalid_credentials'),
            ], 401);
        }

        $user = Auth::guard('admin')->user();
        $token = $user->createToken($credentials['device_name'])->plainTextToken;

        return response()->json([
            'message' => trans('api-resources.auth.login.success'),
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('sanctum')->currentAccessToken()->delete();

        return response()->json([
            'message' => trans('api-resources.auth.logout.success'),
        ]);
    }

    /**
     * Get logged in admin user's details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $admin = $request->user('sanctum');

        return response()->json([
            'message' => trans('api-resources.auth.get.success'),
            'data'    => [
                'id'        => $admin->id,
                'name'      => $admin->name,
                'email'     => $admin->email,
                'status'    => $admin->status,
                'image'     => $admin->image,
                'image_url' => $admin->image_url,
            ],
        ]);
    }

    /**
     * Update admin user's profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $admin = $request->user('sanctum');

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:admins,email,'.$admin->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        // Update only the fields that were provided
        if (isset($validated['name'])) {
            $admin->name = $validated['name'];
        }

        if (isset($validated['email'])) {
            $admin->email = $validated['email'];
        }

        if (isset($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return response()->json([
            'message' => trans('api-resources.auth.update.success'),
            'data'    => [
                'id'        => $admin->id,
                'name'      => $admin->name,
                'email'     => $admin->email,
                'status'    => $admin->status,
                'image'     => $admin->image,
                'image_url' => $admin->image_url,
            ],
        ]);
    }

    /**
     * Send password reset link
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        try {
            // Send password reset link
            $status = Password::broker('admins')->sendResetLink(
                $request->only('email')
            );

            // Check if password reset was sent successfully
            if ($status == Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => trans('api-resources.auth.forgot_password.link_sent'),
                    'status'  => 'success',
                ], 200);
            } elseif ($status == Password::INVALID_USER) {
                return response()->json([
                    'error' => trans('api-resources.auth.forgot_password.user_not_found'),
                ], 404);
            } else {
                return response()->json([
                    'error'       => trans('api-resources.auth.forgot_password.failed'),
                    'status_code' => $status,
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Password reset error: '.$e->getMessage());

            return response()->json([
                'error'   => trans('api-resources.auth.forgot_password.try_again'),
                'details' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
