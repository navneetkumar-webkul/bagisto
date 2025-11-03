<?php

namespace Webkul\ApiResources\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Webkul\Customer\Models\Customer;

class ShopAuthController extends Controller
{
    /**
     * Register a new customer
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:customers,email',
            'password'   => 'required|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'channel_id' => 1, // Default channel, adjust as needed
        ]);

        $token = $customer->createToken('shop-token')->plainTextToken;

        return response()->json([
            'message' => 'Customer registered successfully',
            'data'    => [
                'customer' => $customer,
                'token'    => $token,
            ],
        ], 201);
    }

    /**
     * Login customer
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $customer = Customer::where('email', $validated['email'])->first();

        if (! $customer || ! Hash::check($validated['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Invalidate previous tokens if needed (optional)
        // $customer->tokens()->delete();

        $token = $customer->createToken('shop-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data'    => [
                'customer' => $customer,
                'token'    => $token,
            ],
        ]);
    }

    /**
     * Get current customer profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = $request->user();

        $validated = $request->validate([
            'first_name' => 'string|max:255',
            'last_name'  => 'string|max:255',
            'email'      => 'email|unique:customers,email,'.$customer->id,
            'phone'      => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data'    => $customer,
        ]);
    }

    /**
     * Change customer password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $customer = $request->user();

        if (! Hash::check($validated['current_password'], $customer->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password is incorrect.'],
            ]);
        }

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Logout customer
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Request password reset
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        // TODO: Implement password reset logic
        // Send reset link via email

        return response()->json([
            'message' => 'If a matching customer exists, a password reset link will be sent.',
        ]);
    }

    /**
     * Reset password with token
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:customers,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // TODO: Implement password reset token verification
        // Verify token and update password

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }
}
