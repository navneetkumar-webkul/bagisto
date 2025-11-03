# API Resources - Multi-Language Localization Guide

## Overview

All validation messages and auth responses in the API Resources package are now localized for 20 different languages.

## Directory Structure

```
packages/Webkul/ApiResources/
├── src/
│   ├── Resources/
│   │   └── lang/
│   │       ├── en/api-resources.php
│   │       ├── ar/api-resources.php
│   │       ├── zh_CN/api-resources.php
│   │       ├── fr/api-resources.php
│   │       ├── de/api-resources.php
│   │       ├── es/api-resources.php
│   │       ├── hi_IN/api-resources.php
│   │       ├── bn/api-resources.php
│   │       ├── ca/api-resources.php
│   │       ├── fa/api-resources.php
│   │       ├── he/api-resources.php
│   │       ├── it/api-resources.php
│   │       ├── ja/api-resources.php
│   │       ├── nl/api-resources.php
│   │       ├── pl/api-resources.php
│   │       ├── pt_BR/api-resources.php
│   │       ├── ru/api-resources.php
│   │       ├── sin/api-resources.php
│   │       ├── tr/api-resources.php
│   │       └── uk/api-resources.php
```

## Supported Languages

### Fully Translated (7 languages)
- **English (en)** - Complete translations
- **Arabic (ar)** - كامل الترجمة
- **Chinese (zh_CN)** - 完整翻译
- **French (fr)** - Traductions complètes
- **German (de)** - Vollständige Übersetzungen
- **Spanish (es)** - Traducciones completas
- **Hindi (hi_IN)** - संपूर्ण अनुवाद

### English Fallback (13 languages)
Bengali (bn), Catalan (ca), Persian (fa), Hebrew (he), Italian (it), Japanese (ja), Dutch (nl), Polish (pl), Portuguese Brazil (pt_BR), Russian (ru), Sinhala (sin), Turkish (tr), Ukrainian (uk)

## Translation Keys

### Auth Messages

```php
api-resources.auth.login.invalid_credentials
api-resources.auth.login.success
api-resources.auth.logout.success
api-resources.auth.get.success
api-resources.auth.update.success
api-resources.auth.forgot_password.link_sent
api-resources.auth.forgot_password.user_not_found
api-resources.auth.forgot_password.failed
api-resources.auth.forgot_password.try_again
```

### Channel Validation Messages

```php
api-resources.validations.channel.code_required
api-resources.validations.channel.code_unique
api-resources.validations.channel.name_required
api-resources.validations.channel.default_locale_id_required
api-resources.validations.channel.default_locale_id_exists
api-resources.validations.channel.locales_required
api-resources.validations.channel.locales_exists
api-resources.validations.channel.inventory_sources_required
api-resources.validations.channel.inventory_sources_exists
api-resources.validations.channel.root_category_id_required
api-resources.validations.channel.root_category_id_exists
api-resources.validations.channel.currencies_required
api-resources.validations.channel.currencies_exists
api-resources.validations.channel.base_currency_id_required
api-resources.validations.channel.base_currency_id_exists
api-resources.validations.channel.meta_title_required
api-resources.validations.channel.meta_keywords_required
api-resources.validations.channel.meta_description_required
api-resources.validations.channel.hostname_unique
```

## Usage

### In Controllers

```php
use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
    if (!Auth::guard('admin')->attempt($credentials)) {
        return response()->json([
            'error' => trans('api-resources.auth.login.invalid_credentials'),
        ], 401);
    }

    return response()->json([
        'message' => trans('api-resources.auth.login.success'),
        'token' => $token,
    ]);
}
```

### In Form Requests

```php
use Illuminate\Foundation\Http\FormRequest;

class CreateChannelRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'code.required' => trans('api-resources.validations.channel.code_required'),
            'code.unique' => trans('api-resources.validations.channel.code_unique'),
            // ... more messages
        ];
    }
}
```

## Service Provider Configuration

The `ApiResourcesServiceProvider` is already configured to load translations:

```php
public function boot(): void
{
    // Register language files
    $this->loadTranslationsFrom(
        __DIR__.'/../Resources/lang',
        'api-resources'
    );
}
```

**Namespace:** `api-resources`
**Path:** `/packages/Webkul/ApiResources/src/Resources/lang`

## How Localization Works

1. **User's Locale Setting**: Laravel uses the `app.locale` config or Accept-Language header
2. **Translation Loading**: The framework automatically loads translations for the active locale
3. **Fallback**: If a translation is not found in the active locale, it falls back to English
4. **Response**: The API response uses the localized message based on the user's language

## Setting User Locale

### Via Config
```php
// config/app.php
'locale' => 'fr', // Set default locale to French
```

### Via Accept-Language Header
```bash
curl -H "Accept-Language: ar" https://api.example.com/api/v1/admin/channels
```

### Via Query Parameter (if implemented)
```php
Route::group(['middleware' => ['locale']], function () {
    // Routes
});

// Middleware to set locale from query parameter
```

## Example API Response

### English Response
```json
{
  "error": "Invalid email or password."
}
```

### French Response
```json
{
  "error": "Email ou mot de passe invalide."
}
```

### Arabic Response
```json
{
  "error": "البريد الإلكتروني أو كلمة المرور غير صحيحة."
}
```

## Adding New Translations

To add a new translation key:

1. Add the key to all language files in `src/Resources/lang/{locale}/api-resources.php`
2. Use the key with `trans()` function in your code:
   ```php
   trans('api-resources.section.key')
   ```

Example:
```php
// In src/Resources/lang/en/api-resources.php
return [
    'messages' => [
        'welcome' => 'Welcome to our API',
    ],
];

// In your controller
return response()->json([
    'message' => trans('api-resources.messages.welcome'),
]);
```

## Testing Localization

```bash
# Test with English
curl -H "Accept-Language: en" https://api.example.com/api/v1/admin/channels

# Test with French
curl -H "Accept-Language: fr" https://api.example.com/api/v1/admin/channels

# Test with Arabic
curl -H "Accept-Language: ar" https://api.example.com/api/v1/admin/channels

# Test with Chinese
curl -H "Accept-Language: zh" https://api.example.com/api/v1/admin/channels
```

## Contributing Translations

To improve translations for non-English languages:

1. Edit the respective language file in `src/Resources/lang/{locale}/api-resources.php`
2. Provide accurate translations for all keys
3. Test the translations with the API
4. Submit your improvements

## Cache Considerations

Laravel caches translations. After making changes to translation files:

```bash
# Clear Laravel cache
php artisan cache:clear

# Clear config cache
php artisan config:clear
```

## Notes

- All translation keys use the `api-resources` namespace
- The fallback language is English (en)
- Missing translations will display the English text
- Translations support special characters and Unicode
- All authentication and validation messages are translatable

