<?php

namespace Webkul\ApiResources\Tests\Concerns;

use Webkul\User\Models\Admin as AdminModel;

trait ApiResourcesTestBench
{
    /**
     * Create a test admin user.
     *
     * @param array $attributes Custom attributes for the admin
     * @return \Webkul\User\Models\Admin
     */
    public function createTestAdmin(array $attributes = [])
    {
        return AdminModel::factory()->create($attributes);
    }

    /**
     * Login as admin and get authentication token.
     *
     * @param \Webkul\User\Models\Admin|null $admin
     * @return array Array containing the admin and token
     */
    public function loginAsAdminAPI($admin = null)
    {
        $admin = $admin ?? $this->createTestAdmin();

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'password', // Default password from factory
            'device_name' => 'test-device',
        ]);

        $token = $response->json('token');

        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    /**
     * Get default admin credentials for testing.
     *
     * @return array
     */
    public function getAdminCredentials()
    {
        return [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'test-device',
        ];
    }

    /**
     * Create authenticated request headers.
     *
     * @param string $token
     * @return array
     */
    public function getAuthHeaders(string $token)
    {
        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
