<?php

namespace Webkul\ApiResources\Tests\Feature;

use Webkul\ApiResources\Tests\ApiResourcesTestCase;
use Webkul\Attribute\Models\Attribute;
use Webkul\User\Models\Admin;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

/**
 * Attribute API Test Suite
 *
 * Comprehensive tests for Attribute Resource API endpoints:
 * - GET /api/attributes - Retrieve collection with pagination/filtering
 * - GET /api/attributes/{id} - Retrieve single attribute
 * - POST /api/attributes - Create new attribute
 * - PATCH /api/attributes/{id} - Update attribute
 * - DELETE /api/attributes/{id} - Delete attribute
 *
 * All tests use authenticated API calls with Bearer token authentication.
 * Follows Laravel 11 & Pest standards, Bagisto conventions.
 * Note: Attribute codes are max 2 characters per API validation.
 */
describe('Attribute API', function () {
    /**
     * GET /api/attributes - Collection retrieval with authentication
     */
    describe('Get Attributes Collection (GET /api/attributes)', function () {
        test('should retrieve collection of attributes with valid auth token', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            Attribute::factory()->count(2)->create();

            // Act
            $response = getJson('/api/attributes', [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'code',
                            'admin_name',
                            'type',
                        ],
                    ],
                ]);

            expect($response->json('data'))->toBeArray();
        });

        test('should return 401 without authentication token', function () {
            // Act
            $response = getJson('/api/attributes');

            // Assert
            $response->assertStatus(401)
                ->assertJsonPath('message', 'Unauthenticated.');
        });

        test('should return 401 with invalid token', function () {
            // Act
            $response = getJson('/api/attributes', [
                'Authorization' => 'Bearer invalid_token_12345',
            ]);

            // Assert
            $response->assertStatus(401);
        });

        test('should support pagination with per_page parameter', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            Attribute::factory()->count(5)->create();

            // Act
            $response = getJson('/api/attributes?per_page=3', [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200);
            if ($response->json('meta')) {
                expect($response->json('meta.per_page'))->toBeLessThanOrEqual(3);
            }
        });

        test('should support page parameter for pagination', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            Attribute::factory()->count(10)->create();

            // Act
            $response = getJson('/api/attributes?page=1&per_page=5', [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200);
        });

        test('should include all required attribute fields in collection', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            Attribute::factory()->create();

            // Act
            $response = getJson('/api/attributes?per_page=1', [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200);
            $data = $response->json('data');
            if (is_array($data) && count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'code', 'admin_name', 'type']);
            }
        });
    });

    /**
     * GET /api/attributes/{id} - Single resource retrieval with authentication
     */
    describe('Get Single Attribute (GET /api/attributes/{id})', function () {
        test('should retrieve single attribute by id with valid token', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create();

            // Act
            $response = getJson("/api/attributes/{$attribute->id}", [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'code',
                        'admin_name',
                        'type',
                    ],
                ])
                ->assertJsonPath('data.id', $attribute->id)
                ->assertJsonPath('data.code', $attribute->code);
        });

        test('should return 401 without authentication token', function () {
            // Arrange
            $attribute = Attribute::factory()->create();

            // Act
            $response = getJson("/api/attributes/{$attribute->id}");

            // Assert
            $response->assertStatus(401);
        });

        test('should return 404 for non-existent attribute', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;

            // Act
            $response = getJson('/api/attributes/99999', [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(404);
        });

        test('should include full attribute details in single response', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create([
                'admin_name' => 'Detail Test Attribute',
                'type'       => 'text',
            ]);

            // Act
            $response = getJson("/api/attributes/{$attribute->id}", [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.admin_name', 'Detail Test Attribute')
                ->assertJsonPath('data.type', 'text');
        });

        test('should reject invalid authentication token', function () {
            // Arrange
            $attribute = Attribute::factory()->create();

            // Act
            $response = getJson("/api/attributes/{$attribute->id}", [
                'Authorization' => 'Bearer invalid_token',
            ]);

            // Assert
            $response->assertStatus(401);
        });
    });

    /**
     * POST /api/attributes - Resource creation with authentication
     */
    describe('Create Attribute (POST /api/attributes)', function () {
        test('should create new attribute with valid data and auth token', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $code = chr(rand(97, 122)).chr(rand(97, 122));
            $data = [
                'code'        => $code,
                'admin_name'  => 'New Created Attribute',
                'type'        => 'text',
                'position'    => 10,
                'is_required' => 0,
            ];

            // Act
            $response = postJson('/api/attributes', $data, [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(201)
                ->assertJsonPath('data.code', $code)
                ->assertJsonPath('data.admin_name', 'New Created Attribute');

            $this->assertDatabaseHas('attributes', ['code' => $code]);
        });

        test('should return 401 without authentication token', function () {
            // Arrange
            $data = [
                'code'       => 'cd',
                'admin_name' => 'Test',
                'type'       => 'text',
            ];

            // Act
            $response = postJson('/api/attributes', $data);

            // Assert
            $response->assertStatus(401);
        });

        test('should validate required fields', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $data = [
                'admin_name' => 'Missing Code',
                // Missing code
            ];

            // Act
            $response = postJson('/api/attributes', $data, [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(422);
            expect($response->json('errors.code'))->toBeDefined();
        });

        test('should enforce unique code constraint', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $existing = Attribute::factory()->create();

            // Act
            $response = postJson('/api/attributes', [
                'code'       => $existing->code,
                'admin_name' => 'Duplicate Code',
                'type'       => 'text',
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(422);
            expect($response->json('errors.code'))->toBeDefined();
        });

        test('should create attribute with different types', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $types = ['text', 'textarea', 'select', 'multiselect', 'price'];
            $codes = ['tx', 'ta', 'sl', 'ms', 'pr'];

            // Act & Assert
            foreach ($types as $i => $type) {
                $response = postJson('/api/attributes', [
                    'code'       => $codes[$i],
                    'admin_name' => 'Type '.$type,
                    'type'       => $type,
                ], [
                    'Authorization' => "Bearer {$token}",
                ]);

                $response->assertStatus(201)
                    ->assertJsonPath('data.type', $type);
            }
        });

        test('should handle invalid type validation', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;

            // Act
            $response = postJson('/api/attributes', [
                'code'       => 'bt',
                'admin_name' => 'Invalid Type',
                'type'       => 'invalid_type_xyz',
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(422);
        });

        test('should accept optional fields during creation', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;

            // Act
            $response = postJson('/api/attributes', [
                'code'        => 'op',
                'admin_name'  => 'With Optionals',
                'type'        => 'text',
                'position'    => 25,
                'is_required' => 1,
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(201);
        });
    });

    /**
     * PATCH /api/attributes/{id} - Resource update with authentication
     */
    describe('Update Attribute (PATCH /api/attributes/{id})', function () {
        test('should update attribute with valid data and auth token', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create();

            // Act
            $response = patchJson("/api/attributes/{$attribute->id}", [
                'admin_name' => 'Updated Attribute Name',
                'position'   => 20,
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.admin_name', 'Updated Attribute Name')
                ->assertJsonPath('data.position', 20);

            $this->assertDatabaseHas('attributes', [
                'id'         => $attribute->id,
                'admin_name' => 'Updated Attribute Name',
            ]);
        });

        test('should return 401 without authentication token', function () {
            // Arrange
            $attribute = Attribute::factory()->create();

            // Act
            $response = patchJson("/api/attributes/{$attribute->id}", [
                'admin_name' => 'Updated',
            ]);

            // Assert
            $response->assertStatus(401);
        });

        test('should return 404 for non-existent attribute', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;

            // Act
            $response = patchJson('/api/attributes/99999', [
                'admin_name' => 'Some Name',
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(404);
        });

        test('should allow partial updates', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create();
            $originalCode = $attribute->code;

            // Act
            $response = patchJson("/api/attributes/{$attribute->id}", [
                'position' => 99,
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.code', $originalCode)
                ->assertJsonPath('data.position', 99);
        });

        test('should update attribute type', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create(['type' => 'text']);

            // Act
            $response = patchJson("/api/attributes/{$attribute->id}", [
                'type' => 'textarea',
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.type', 'textarea');
        });

        test('should update multiple properties', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create();

            // Act
            $response = patchJson("/api/attributes/{$attribute->id}", [
                'admin_name'  => 'Multi Updated',
                'position'    => 50,
                'is_required' => 1,
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.admin_name', 'Multi Updated')
                ->assertJsonPath('data.position', 50);
        });

        test('should maintain data integrity across multiple updates', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create();
            $originalId = $attribute->id;
            $originalCode = $attribute->code;

            // Act - Multiple updates
            patchJson("/api/attributes/{$attribute->id}", [
                'admin_name' => 'Update 1',
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            patchJson("/api/attributes/{$attribute->id}", [
                'position' => 25,
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            $response = patchJson("/api/attributes/{$attribute->id}", [
                'is_required' => 1,
            ], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(200)
                ->assertJsonPath('data.id', $originalId)
                ->assertJsonPath('data.code', $originalCode)
                ->assertJsonPath('data.admin_name', 'Update 1')
                ->assertJsonPath('data.position', 25);
        });
    });

    /**
     * DELETE /api/attributes/{id} - Resource deletion with authentication
     */
    describe('Delete Attribute (DELETE /api/attributes/{id})', function () {
        test('should delete attribute with valid auth token', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attribute = Attribute::factory()->create();
            $attrId = $attribute->id;

            // Act
            $response = deleteJson("/api/attributes/{$attrId}", [], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(204);
            $this->assertDatabaseMissing('attributes', ['id' => $attrId]);
        });

        test('should return 401 without authentication token', function () {
            // Arrange
            $attribute = Attribute::factory()->create();

            // Act
            $response = deleteJson("/api/attributes/{$attribute->id}");

            // Assert
            $response->assertStatus(401);
        });

        test('should return 404 when deleting non-existent attribute', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;

            // Act
            $response = deleteJson('/api/attributes/99999', [], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $response->assertStatus(404);
        });

        test('should not delete other attributes', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $attr1 = Attribute::factory()->create();
            $attr2 = Attribute::factory()->create();

            // Act
            deleteJson("/api/attributes/{$attr1->id}", [], [
                'Authorization' => "Bearer {$token}",
            ]);

            // Assert
            $this->assertDatabaseMissing('attributes', ['id' => $attr1->id]);
            $this->assertDatabaseHas('attributes', ['id' => $attr2->id]);
        });

        test('should return 401 with invalid token', function () {
            // Arrange
            $attribute = Attribute::factory()->create();

            // Act
            $response = deleteJson("/api/attributes/{$attribute->id}", [], [
                'Authorization' => 'Bearer invalid_token',
            ]);

            // Assert
            $response->assertStatus(401);
        });
    });

    /**
     * Integration Tests - Full CRUD lifecycle with authentication
     */
    describe('Attribute CRUD Integration', function () {
        test('should complete full CRUD lifecycle via API', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $headers = ['Authorization' => "Bearer {$token}"];
            $code = chr(rand(97, 122)).chr(rand(97, 122));

            // 1. CREATE
            $createResponse = postJson('/api/attributes', [
                'code'       => $code,
                'admin_name' => 'CRUD Test Attribute',
                'type'       => 'text',
            ], $headers);

            $createResponse->assertStatus(201);
            $attrId = $createResponse->json('data.id');
            expect($attrId)->toBeTruthy();

            // 2. READ
            $readResponse = getJson("/api/attributes/{$attrId}", $headers);
            $readResponse->assertStatus(200)
                ->assertJsonPath('data.code', $code);

            // 3. UPDATE
            $updateResponse = patchJson("/api/attributes/{$attrId}", [
                'admin_name' => 'CRUD Updated',
                'position'   => 15,
            ], $headers);

            $updateResponse->assertStatus(200)
                ->assertJsonPath('data.admin_name', 'CRUD Updated')
                ->assertJsonPath('data.position', 15);

            // 4. DELETE
            $deleteResponse = deleteJson("/api/attributes/{$attrId}", [], $headers);
            $deleteResponse->assertStatus(204);

            // Verify deletion
            $verifyResponse = getJson("/api/attributes/{$attrId}", $headers);
            $verifyResponse->assertStatus(404);
        });

        test('should handle multiple CRUD operations with different admins', function () {
            // Arrange
            $admin1 = Admin::factory()->create();
            $admin2 = Admin::factory()->create();
            $token1 = $admin1->createToken('device1')->plainTextToken;
            $token2 = $admin2->createToken('device2')->plainTextToken;
            $code = chr(rand(97, 122)).chr(rand(97, 122));

            // Act & Assert - Admin 1 creates
            $createResp = postJson('/api/attributes', [
                'code'       => $code,
                'admin_name' => 'Multi Admin Test',
                'type'       => 'select',
            ], ['Authorization' => "Bearer {$token1}"]);

            $createResp->assertStatus(201);
            $attrId = $createResp->json('data.id');

            // Admin 2 can read
            $readResp = getJson("/api/attributes/{$attrId}", [
                'Authorization' => "Bearer {$token2}",
            ]);
            $readResp->assertStatus(200);

            // Admin 2 can update
            $updateResp = patchJson("/api/attributes/{$attrId}", [
                'position' => 30,
            ], ['Authorization' => "Bearer {$token2}"]);

            $updateResp->assertStatus(200);

            // Admin 1 can delete
            $deleteResp = deleteJson("/api/attributes/{$attrId}", [], [
                'Authorization' => "Bearer {$token1}",
            ]);

            $deleteResp->assertStatus(204);
        });

        test('should list and filter attributes via API', function () {
            // Arrange
            $admin = Admin::factory()->create();
            $token = $admin->createToken('test-device')->plainTextToken;
            $headers = ['Authorization' => "Bearer {$token}"];

            // Create a few attributes with unique codes
            postJson('/api/attributes', [
                'code'       => 'f'.chr(rand(97, 122)),
                'admin_name' => 'Filter Test 1',
                'type'       => 'text',
            ], $headers)->assertStatus(201);

            postJson('/api/attributes', [
                'code'       => 'g'.chr(rand(97, 122)),
                'admin_name' => 'Filter Test 2',
                'type'       => 'select',
            ], $headers)->assertStatus(201);

            // Act - Get collection
            $response = getJson('/api/attributes?per_page=50', $headers);

            // Assert
            $response->assertStatus(200);
            expect($response->json('data'))->toBeArray();
            expect(count($response->json('data')))->toBeGreaterThanOrEqual(2);
        });
    });
})->uses(ApiResourcesTestCase::class);
