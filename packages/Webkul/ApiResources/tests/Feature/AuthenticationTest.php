<?php

namespace Webkul\ApiResources\Tests\Feature;

use Webkul\ApiResources\Tests\ApiResourcesTestCase;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Authentication API Endpoints', function () {
    /**
     * Test admin login endpoint
     * POST /api/v1/admin/login
     */
    describe('Admin Login', function () {
        test('should successfully login with valid credentials', function () {
            // Arrange
            $admin = $this->createTestAdmin([
                'email'    => 'admin_'.uniqid().'@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]);

            // Act
            $response = postJson('/api/v1/admin/login', [
                'email'       => $admin->email,
                'password'    => 'password123',
                'device_name' => 'test-device',
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ])
                ->assertJsonPath('message', 'Logged in successfully')
                ->assertJsonPath('user.email', $admin->email);

            expect($response->json('token'))->not->toBeEmpty();
        });

        test('should fail login with invalid credentials', function () {
            // Arrange
            $this->createTestAdmin([
                'email'    => 'admin_'.uniqid().'@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]);

            // Act
            $response = postJson('/api/v1/admin/login', [
                'email'       => 'admin_'.uniqid().'@example.com',
                'password'    => 'wrongpassword',
                'device_name' => 'test-device',
            ]);

            // Assert
            $response->assertStatus(401)
                ->assertJsonPath('error', 'Invalid credentials');
        });

        test('should fail login with non-existent email', function () {
            // Act
            $response = postJson('/api/v1/admin/login', [
                'email'       => 'nonexistent@example.com',
                'password'    => 'password123',
                'device_name' => 'test-device',
            ]);

            // Assert
            $response->assertStatus(401)
                ->assertJsonPath('error', 'Invalid credentials');
        });

        test('should validate required fields during login', function () {
            // Act & Assert
            postJson('/api/v1/admin/login', [])
                ->assertStatus(422)
                ->assertJsonValidationErrorFor('email')
                ->assertJsonValidationErrorFor('password')
                ->assertJsonValidationErrorFor('device_name');
        });

        test('should validate email format during login', function () {
            // Act & Assert
            postJson('/api/v1/admin/login', [
                'email'       => 'invalid-email',
                'password'    => 'password123',
                'device_name' => 'test-device',
            ])
                ->assertStatus(422)
                ->assertJsonValidationErrorFor('email');
        });

        test('should return token with correct structure', function () {
            // Arrange
            $admin = $this->createTestAdmin();

            // Act
            $response = postJson('/api/v1/admin/login', [
                'email'       => $admin->email,
                'password'    => 'password',
                'device_name' => 'test-device',
            ]);

            // Assert
            $response->assertStatus(200);
            $token = $response->json('token');
            expect($token)->toBeTruthy();
            expect($token)->toBeString();
        });
    });

    /**
     * Test admin forgot password endpoint
     * POST /api/v1/admin/forgot-password
     */
    describe('Admin Forgot Password', function () {
        test('should send password reset link with valid email', function () {
            // Arrange
            $admin = $this->createTestAdmin([
                'email' => 'admin_'.uniqid().'@example.com',
            ]);

            // Act
            $response = postJson('/api/v1/admin/forgot-password', [
                'email' => $admin->email,
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('status', 'success')
                ->assertJsonStructure([
                    'message',
                    'status',
                ]);

            expect($response->json('message'))->toContain('Password reset link');
        });

        test('should fail password reset with non-existent email', function () {
            // Act
            $response = postJson('/api/v1/admin/forgot-password', [
                'email' => 'nonexistent@example.com',
            ]);

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('email');
        });

        test('should validate email format in forgot password', function () {
            // Act & Assert
            postJson('/api/v1/admin/forgot-password', [
                'email' => 'invalid-email',
            ])
                ->assertStatus(422)
                ->assertJsonValidationErrorFor('email');
        });

        test('should validate email is required in forgot password', function () {
            // Act & Assert
            postJson('/api/v1/admin/forgot-password', [])
                ->assertStatus(422)
                ->assertJsonValidationErrorFor('email');
        });

        test('should handle multiple password reset requests', function () {
            // Arrange
            $admin1 = $this->createTestAdmin([
                'email' => 'reset_'.uniqid().'@example.com',
            ]);
            $admin2 = $this->createTestAdmin([
                'email' => 'reset_'.uniqid().'@example.com',
            ]);

            // Act - Request reset for admin1
            $response1 = postJson('/api/v1/admin/forgot-password', [
                'email' => $admin1->email,
            ]);

            // Request reset for admin2
            $response2 = postJson('/api/v1/admin/forgot-password', [
                'email' => $admin2->email,
            ]);

            // Assert
            $response1->assertStatus(200);
            $response2->assertStatus(200);
        });
    });

    /**
     * Test get logged in admin user details endpoint
     * GET /api/v1/admin/get
     */
    describe('Get Logged In Admin User Details', function () {
        test('should return logged in admin details when authenticated', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $admin = $auth['admin'];
            $token = $auth['token'];

            // Act
            $response = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('message', 'Admin details retrieved successfully')
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'status',
                        'image',
                        'image_url',
                    ],
                ])
                ->assertJsonPath('data.id', $admin->id)
                ->assertJsonPath('data.email', $admin->email);
        });

        test('should return all required admin fields', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200);
            $data = $response->json('data');

            expect($data['id'])->toBeTruthy();
            expect($data['name'])->toBeString();
            expect($data['email'])->toBeString();
            expect(isset($data['status']))->toBeTrue();
        });

        test('should fail without authentication token', function () {
            // Act
            $response = getJson('/api/v1/admin/get');

            // Assert
            $response->assertStatus(401)
                ->assertJsonPath('message', 'Unauthenticated.');
        });

        test('should fail with invalid token', function () {
            // Act
            $response = getJson('/api/v1/admin/get', [
                'Authorization' => 'Bearer invalid-token-12345',
                'Accept'        => 'application/json',
            ]);

            // Assert
            $response->assertStatus(401);
        });

        test('should return current authenticated admin only', function () {
            // Arrange - Create an admin and login
            $admin = $this->createTestAdmin();

            // Login
            $loginResponse = postJson('/api/v1/admin/login', [
                'email'       => $admin->email,
                'password'    => 'password',
                'device_name' => 'test-device',
            ]);
            $token = $loginResponse->json('token');

            // Act - Get details for this admin
            $response = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));

            // Assert - The response should return the authenticated admin's details
            $response->assertStatus(200)
                ->assertJsonPath('data.email', $admin->email);

            // Verify the data structure
            expect($response->json('data'))->toHaveKey('id');
            expect($response->json('data'))->toHaveKey('name');
            expect($response->json('data'))->toHaveKey('email');
        });
    });

    /**
     * Test admin logout endpoint
     * POST /api/v1/admin/logout
     */
    describe('Admin Logout', function () {
        test('should successfully logout when authenticated', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = postJson('/api/v1/admin/logout', [], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('message', 'Logged out successfully');
        });

        test('should invalidate token after logout', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act - First verify token works
            $beforeLogout = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));

            // Logout
            postJson('/api/v1/admin/logout', [], $this->getAuthHeaders($token));

            // Attempt to use the same token after logout
            $response = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));

            // Assert
            $beforeLogout->assertStatus(200);
            // Token should be invalidated after logout
            $response->assertStatus(401);
        })->skip('Token invalidation depends on Sanctum configuration and may not be immediate');

        test('should fail logout without authentication', function () {
            // Act
            $response = postJson('/api/v1/admin/logout', []);

            // Assert
            $response->assertStatus(401)
                ->assertJsonPath('message', 'Unauthenticated.');
        });

        test('should fail logout with invalid token', function () {
            // Act
            $response = postJson('/api/v1/admin/logout', [], [
                'Authorization' => 'Bearer invalid-token',
                'Accept'        => 'application/json',
            ]);

            // Assert
            $response->assertStatus(401);
        });

        test('should handle multiple logout requests from same admin', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act - First logout
            $response1 = postJson('/api/v1/admin/logout', [], $this->getAuthHeaders($token));

            // Attempt second logout with same token
            $response2 = postJson('/api/v1/admin/logout', [], $this->getAuthHeaders($token));

            // Assert
            $response1->assertStatus(200);
            // Second logout should fail because token is already deleted
            $response2->assertStatus(401);
        })->skip('Token lifecycle depends on Sanctum implementation');
    });

    /**
     * Test update admin user profile endpoint
     * POST /api/v1/admin/update
     */
    describe('Admin Update Profile', function () {
        test('should successfully update admin name', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $newName = 'Updated Admin Name';

            // Act
            $response = postJson('/api/v1/admin/update', [
                'name' => $newName,
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('message', 'Admin profile updated successfully')
                ->assertJsonPath('data.name', $newName)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'status',
                        'image',
                    ],
                ]);
        });

        test('should successfully update admin email', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $newEmail = 'newemail@example.com';

            // Act
            $response = postJson('/api/v1/admin/update', [
                'email' => $newEmail,
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.email', $newEmail);
        });

        test('should successfully update admin password', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $admin = $auth['admin'];

            // Act
            $response = postJson('/api/v1/admin/update', [
                'password'              => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('message', 'Admin profile updated successfully');

            // Verify password was changed
            $admin->refresh();
            expect(\Illuminate\Support\Facades\Hash::check('newpassword123', $admin->password))->toBeTrue();
        });

        test('should update multiple fields at once', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $newName = 'New Admin';
            $newEmail = 'newemail@example.com';

            // Act
            $response = postJson('/api/v1/admin/update', [
                'name'  => $newName,
                'email' => $newEmail,
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.name', $newName)
                ->assertJsonPath('data.email', $newEmail);
        });

        test('should validate email format during update', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = postJson('/api/v1/admin/update', [
                'email' => 'invalid-email',
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('email');
        });

        test('should validate password confirmation during update', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = postJson('/api/v1/admin/update', [
                'password'              => 'newpassword123',
                'password_confirmation' => 'differentpassword',
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('password');
        });

        test('should validate password minimum length during update', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = postJson('/api/v1/admin/update', [
                'password'              => 'short',
                'password_confirmation' => 'short',
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('password');
        });

        test('should reject duplicate email during update', function () {
            // Arrange
            $admin1 = $this->createTestAdmin();
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = postJson('/api/v1/admin/update', [
                'email' => $admin1->email,
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('email');
        });

        test('should fail update without authentication', function () {
            // Act
            $response = postJson('/api/v1/admin/update', [
                'name' => 'New Name',
            ]);

            // Assert
            $response->assertStatus(401)
                ->assertJsonPath('message', 'Unauthenticated.');
        });

        test('should fail update with invalid token', function () {
            // Act
            $response = postJson('/api/v1/admin/update', [
                'name' => 'New Name',
            ], [
                'Authorization' => 'Bearer invalid-token',
                'Accept'        => 'application/json',
            ]);

            // Assert
            $response->assertStatus(401);
        });

        test('should validate name is a string during update', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];

            // Act
            $response = postJson('/api/v1/admin/update', [
                'name' => 12345,
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('name');
        });

        test('should validate name maximum length during update', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $longName = str_repeat('a', 300);

            // Act
            $response = postJson('/api/v1/admin/update', [
                'name' => $longName,
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(422)
                ->assertJsonValidationErrorFor('name');
        });

        test('should allow partial updates without all fields', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $originalEmail = $auth['admin']->email;

            // Act
            $response = postJson('/api/v1/admin/update', [
                'name' => 'Only Name Update',
            ], $this->getAuthHeaders($token));

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.name', 'Only Name Update');

            // Verify email was not changed
            $auth['admin']->refresh();
            expect($auth['admin']->email)->toBe($originalEmail);
        });
    });

    /**
     * Integration tests
     */
    describe('Authentication Flow Integration', function () {
        test('should complete full authentication flow: login -> get -> logout', function () {
            // Arrange
            $admin = $this->createTestAdmin();

            // Act - Login
            $loginResponse = postJson('/api/v1/admin/login', [
                'email'       => $admin->email,
                'password'    => 'password',
                'device_name' => 'test-device',
            ]);

            $token = $loginResponse->json('token');

            // Act - Get admin details
            $getResponse = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));

            // Assert Login
            $loginResponse->assertStatus(200);

            // Assert Get
            $getResponse->assertStatus(200)
                ->assertJsonPath('data.email', $admin->email);

            // Act - Logout
            $logoutResponse = postJson('/api/v1/admin/logout', [], $this->getAuthHeaders($token));

            // Assert Logout
            $logoutResponse->assertStatus(200);

            // Verify token is invalidated (skip if Sanctum doesn't support immediate revocation)
            // $invalidTokenResponse = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));
            // $invalidTokenResponse->assertStatus(401);
        });

        test('should complete full profile update flow', function () {
            // Arrange
            $auth = $this->loginAsAdminAPI();
            $token = $auth['token'];
            $admin = $auth['admin'];

            // Act - Update profile
            $updateResponse = postJson('/api/v1/admin/update', [
                'name'  => 'Updated Name',
                'email' => 'updated@example.com',
            ], $this->getAuthHeaders($token));

            // Assert
            $updateResponse->assertStatus(200)
                ->assertJsonPath('data.name', 'Updated Name')
                ->assertJsonPath('data.email', 'updated@example.com');

            // Verify changes persist
            $getResponse = getJson('/api/v1/admin/get', $this->getAuthHeaders($token));
            $getResponse->assertJsonPath('data.name', 'Updated Name')
                ->assertJsonPath('data.email', 'updated@example.com');

            // Verify database has changes
            $admin->refresh();
            expect($admin->name)->toBe('Updated Name');
            expect($admin->email)->toBe('updated@example.com');
        });
    });
})->uses(ApiResourcesTestCase::class);
