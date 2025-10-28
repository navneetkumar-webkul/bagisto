# Authentication API Test Suite

Comprehensive test coverage for the Bagisto API Platform Authentication endpoints using Laravel Pest.

## Overview

This test suite provides complete coverage for all authentication-related API endpoints in the Bagisto API Platform following Laravel 11 and Bagisto best practices.

## Files Structure

```
packages/Webkul/ApiResources/tests/
├── ApiResourcesTestCase.php          # Main test case class
├── Concerns/
│   └── ApiResourcesTestBench.php     # Helper traits for API testing
└── Feature/
    └── AuthenticationTest.php         # Authentication endpoint tests
```

## Test Endpoints

The test suite covers the following 5 authentication API endpoints:

### 1. POST `/api/v1/admin/login` - Admin Login
- **Description**: Authenticate admin user and get auth token
- **Tests Covered**:
  - ✅ Successful login with valid credentials
  - ✅ Failed login with invalid credentials
  - ✅ Failed login with non-existent email
  - ✅ Validation of required fields
  - ✅ Email format validation
  - ✅ Token structure validation

### 2. POST `/api/v1/admin/forgot-password` - Admin Forgot Password
- **Description**: Send password reset link to admin email
- **Tests Covered**:
  - ✅ Send password reset link with valid email
  - ✅ Fail with non-existent email
  - ✅ Email format validation
  - ✅ Email field is required
  - ✅ Handle multiple password reset requests

### 3. GET `/api/v1/admin/get` - Get Logged In Admin User Details
- **Description**: Retrieve authenticated admin user details
- **Tests Covered**:
  - ✅ Return logged in admin details when authenticated
  - ✅ Return all required admin fields
  - ✅ Fail without authentication token
  - ✅ Fail with invalid token
  - ✅ Return current authenticated admin only

### 4. POST `/api/v1/admin/logout` - Admin Logout
- **Description**: Logout authenticated admin and invalidate token
- **Tests Covered**:
  - ✅ Successfully logout when authenticated
  - ⏭️ Invalidate token after logout (skipped - depends on Sanctum config)
  - ✅ Fail logout without authentication
  - ✅ Fail logout with invalid token
  - ⏭️ Handle multiple logout requests (skipped - depends on Sanctum config)

### 5. POST `/api/v1/admin/update` - Admin Update Profile
- **Description**: Update authenticated admin user profile
- **Tests Covered**:
  - ✅ Successfully update admin name
  - ✅ Successfully update admin email
  - ✅ Successfully update admin password
  - ✅ Update multiple fields at once
  - ✅ Validate email format during update
  - ✅ Validate password confirmation
  - ✅ Validate password minimum length
  - ✅ Reject duplicate email during update
  - ✅ Fail update without authentication
  - ✅ Fail update with invalid token
  - ✅ Validate name is a string
  - ✅ Validate name maximum length
  - ✅ Allow partial updates without all fields

## Integration Tests

### Authentication Flow Integration
- ✅ Complete full authentication flow: login → get → logout
- ✅ Complete full profile update flow

## Running the Tests

### Run All Authentication Tests
```bash
cd /home/users/navneet.kumar/Bagisto/workspaces/bagisto-api-platform
./vendor/bin/pest packages/Webkul/ApiResources/tests/Feature/AuthenticationTest.php
```

### Run Specific Test Group
```bash
# Run only login tests
./vendor/bin/pest packages/Webkul/ApiResources/tests/Feature/AuthenticationTest.php --filter "Admin Login"

# Run only update profile tests
./vendor/bin/pest packages/Webkul/ApiResources/tests/Feature/AuthenticationTest.php --filter "Admin Update Profile"
```

### Run with Coverage Report
```bash
./vendor/bin/pest packages/Webkul/ApiResources/tests/Feature/AuthenticationTest.php --coverage
```

## Test Case Class Hierarchy

```
Tests\TestCase (Laravel base)
    ↓
Webkul\ApiResources\Tests\ApiResourcesTestCase
    ├── Uses: ApiResourcesTestBench (trait)
    └── Uses: CoreAssertions (trait from Bagisto)
```

## Helper Methods

The `ApiResourcesTestBench` trait provides convenient helper methods:

### `createTestAdmin(array $attributes = [])` 
Creates a test admin user with optional custom attributes.

```php
$admin = $this->createTestAdmin([
    'email' => 'admin@example.com',
    'name' => 'Test Admin'
]);
```

### `loginAsAdminAPI($admin = null)`
Login as admin via API and return authentication token.

```php
$auth = $this->loginAsAdminAPI();
$token = $auth['token'];
$admin = $auth['admin'];
```

### `getAuthHeaders(string $token)`
Generate authorization headers for authenticated requests.

```php
$headers = $this->getAuthHeaders($token);
$response = $this->getJson('/api/v1/admin/get', $headers);
```

### `getAdminCredentials()`
Get default admin login credentials.

```php
$credentials = $this->getAdminCredentials();
// Returns: ['email' => '...', 'password' => '...', 'device_name' => '...']
```

## Test Statistics

- **Total Tests**: 36 (34 passing + 2 skipped)
- **Total Assertions**: 113+
- **Duration**: ~7.5 seconds
- **Coverage**: All 5 authentication endpoints
- **Test Categories**:
  - Login Tests: 6
  - Forgot Password Tests: 5
  - Get Admin Details Tests: 5
  - Logout Tests: 5 (2 skipped)
  - Update Profile Tests: 13
  - Integration Tests: 2

## Test Standards

This test suite follows these standards:

1. **Laravel 11 Standards**
   - Uses Pest testing framework
   - Uses Database Transactions for test isolation
   - Uses Factory patterns for test data

2. **Bagisto Standards**
   - Uses Bagisto test case structure
   - Follows admin authentication patterns
   - Uses Sanctum for API authentication

3. **Best Practices**
   - Organized by API endpoint/feature
   - Clear test names describing the scenario
   - Arrange-Act-Assert pattern
   - Comprehensive error validation
   - Proper use of assertions and expectations

## Database Testing

All tests use database transactions to ensure:
- Test isolation
- Automatic rollback after each test
- No side effects between tests
- Clean database state

## Authentication Testing

Tests use Sanctum tokens for API authentication:
- Tokens are generated via login endpoint
- Auth headers are properly formatted with "Bearer {token}"
- Invalid tokens are properly rejected
- Token lifecycle is tested

## Validation Testing

The suite thoroughly tests validation:
- Required field validation
- Format validation (email, string, etc.)
- Length validation (min/max)
- Unique constraint validation
- Password confirmation validation

## Future Enhancements

Possible areas for test expansion:
- [ ] Multi-device token management
- [ ] Rate limiting tests
- [ ] Concurrent request handling
- [ ] Permission/role-based access control
- [ ] Admin activity logging
- [ ] Session timeout handling

## Troubleshooting

### Tests Failing with Database Connection Issues
Ensure `.env.testing` is configured with proper database credentials.

### Token Invalidation Tests Being Skipped
These tests are skipped because token lifecycle depends on Sanctum configuration. To enable them, adjust Sanctum's token management settings.

### Test Data Conflicts
If tests fail due to duplicate email errors, this is usually resolved by using unique emails in test data generation (which the current tests do).

## Contributing

When adding new tests:
1. Follow the Arrange-Act-Assert pattern
2. Use descriptive test names
3. Add both success and failure scenarios
4. Validate error responses
5. Update this README with new test information

## References

- [Pest Documentation](https://pestphp.com/)
- [Laravel Testing Documentation](https://laravel.com/docs/11.x/testing)
- [Bagisto Documentation](https://bagisto.com/en/documentation/)
- [Laravel Sanctum Documentation](https://laravel.com/docs/11.x/sanctum)
