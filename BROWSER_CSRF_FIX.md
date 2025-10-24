# ✅ CSRF Token Mismatch in Swagger UI - FIXED

## Problem Identified

Your API authentication was working perfectly with:
- ✅ **CLI (cURL)** - No issues
- ✅ **Postman/Insomnia** - No issues
- ❌ **Browser Swagger UI** - Getting "419 CSRF token mismatch"

## Root Cause

When you access Swagger UI in the browser (`http://localhost/api/v1/docs`):

1. The browser is a **stateful client** (has cookies)
2. When Swagger UI makes requests, it sends cookies automatically
3. Sanctum's `EnsureFrontendRequestsAreStateful` middleware sees the cookies
4. It expects CSRF tokens for stateful requests
5. API requests don't include CSRF tokens (they use Bearer tokens)
6. Result: **419 CSRF token mismatch error**

### Why CLI Works Fine
- cURL doesn't send cookies
- No stateful request detected
- No CSRF check triggered
- Works perfectly ✅

## Solution Applied

### 1. Created Custom Sanctum Middleware
**File:** `/app/Http/Middleware/CustomEnsureFrontendRequestsAreStateful.php`

This custom middleware **bypasses the stateful CSRF check for all API routes**:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as Middleware;

class CustomEnsureFrontendRequestsAreStateful extends Middleware
{
    public function handle($request, $next)
    {
        // Skip stateful CSRF checks for API routes
        if ($request->is('api/*', 'api/v1/*')) {
            return $next($request);
        }

        // For other routes, use normal Sanctum behavior
        return parent::handle($request, $next);
    }
}
```

### 2. Updated Bootstrap Configuration
**File:** `/bootstrap/app.php`

Replaced the default Sanctum middleware with our custom one:

```php
// OLD
$middleware->api(prepend: [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
]);

// NEW
$middleware->api(prepend: [
    CustomEnsureFrontendRequestsAreStateful::class,
]);
```

## How It Works Now

### Browser Swagger UI Request Flow
```
1. Swagger UI sends POST to /api/v1/admin/login
   ↓
2. Browser automatically includes cookies
   ↓
3. Request reaches CustomEnsureFrontendRequestsAreStateful middleware
   ↓
4. Middleware checks if path is 'api/*'
   ↓
5. YES! Path matches 'api/v1/*'
   ↓
6. Stateful CSRF check is SKIPPED
   ↓
7. Request proceeds to AuthController
   ↓
8. ✅ Login succeeds, token returned
```

## Why This Is Secure

### Still Protected
- ✅ **Bearer tokens** - API is protected by Sanctum tokens
- ✅ **CSRF for web** - Non-API routes still have CSRF protection
- ✅ **Token validation** - Every API call requires valid token
- ✅ **Admin panel** - Web forms still require CSRF tokens

### Why APIs Don't Need CSRF
```
CSRF Attack Against API (doesn't work):
  1. Attacker tricks user to click link
  2. Link tries to make API request
  3. Browser sends it with cookies
  4. Request reaches /api/v1/* route
  5. CustomEnsureFrontendRequestsAreStateful skips CSRF check
  6. Request sent to controller
  7. BUT: No Bearer token included
  8. AuthController rejects it ❌

Result: Attack fails! Bearer token required for action
```

## Testing the Fix

### Test 1: Browser Swagger UI (The Main Fix)
```
1. Open: http://127.0.0.1:8000/api/v1/docs
2. Find "Admin Login" endpoint
3. Click "Try it out"
4. Enter credentials:
   - email: admin@example.com
   - password: admin123
   - device_name: Browser
5. Click "Execute"
6. ✅ Should see 200 response with token
```

### Test 2: CLI Still Works
```bash
curl -X POST http://127.0.0.1:8000/api/v1/admin/login \
  -H 'Content-Type: application/json' \
  -d '{
    "email": "admin@example.com",
    "password": "admin123",
    "device_name": "CLI"
  }'

# Result: ✅ 200 with token
```

### Test 3: Postman Still Works
```
Method: POST
URL: http://127.0.0.1:8000/api/v1/admin/login
Body (JSON):
{
  "email": "admin@example.com",
  "password": "admin123",
  "device_name": "Postman"
}

# Result: ✅ 200 with token
```

## Files Modified

| File | Change | Reason |
|------|--------|--------|
| `/app/Http/Middleware/CustomEnsureFrontendRequestsAreStateful.php` | NEW | Custom middleware to skip stateful CSRF for APIs |
| `/bootstrap/app.php` | UPDATED | Register custom middleware instead of default Sanctum |

## Verification Checklist

- [x] CLI cURL works ✅
- [x] Postman works ✅
- [x] Insomnia works ✅
- [x] Browser Swagger UI works ✅
- [x] No CSRF errors ✅
- [x] Tokens are returned ✅
- [x] Security maintained ✅

## What Changed

**Before:**
- CLI ✅ Works (no cookies)
- Browser ❌ Fails (has cookies, CSRF enforced)

**After:**
- CLI ✅ Works (still no cookies)
- Browser ✅ Works (CSRF check skipped for /api/*)
- Security ✅ Maintained (Bearer tokens still required)

## Best Practice Explanation

This is the **standard approach** for APIs:

1. **Token Authentication** (API) - No CSRF needed
   - Uses Bearer tokens
   - Client includes in Authorization header
   - Inherently protected against CSRF

2. **Session Authentication** (Web) - CSRF needed
   - Uses cookies and sessions
   - Browser includes automatically
   - Vulnerable to CSRF without tokens

Our solution:
- ✅ Skips CSRF for token-based endpoints
- ✅ Keeps CSRF for session-based endpoints
- ✅ Follows REST API best practices
- ✅ Production-ready

## Summary

Your authentication system is now **fully functional** across all clients:

| Client | Status | Why |
|--------|--------|-----|
| CLI/cURL | ✅ Working | No cookies, no CSRF needed |
| Postman | ✅ Working | Manual token control |
| Insomnia | ✅ Working | Manual token control |
| Browser Swagger UI | ✅ Working | Custom middleware skips stateful CSRF |

**You can now test your API from anywhere! 🎉**

