<?php

namespace Webkul\ApiResources\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\Locale;
use Webkul\Core\Models\Country;
use Webkul\Core\Models\CountryState;
use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Customer\Models\CustomerGroup;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;

class ApiPlatformServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Channel::class, function () {
            return Channel::first() ?? Channel::create([
                'code' => 'default',
                'name' => 'Default',
            ]);
        });

        $this->app->singleton(Locale::class, function () {
            return Locale::first() ?? Locale::create([
                'code' => 'en',
                'name' => 'English',
            ]);
        });

        $this->app->singleton(Currency::class, function () {
            return Currency::first() ?? Currency::create([
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
            ]);
        });

        $this->app->bind(\Webkul\Core\Contracts\Channel::class, Channel::class);
        $this->app->bind(\Webkul\Core\Contracts\Currency::class, Currency::class);
        $this->app->bind(\Webkul\Core\Contracts\CurrencyExchangeRate::class, CurrencyExchangeRate::class);
        $this->app->bind(\Webkul\Core\Contracts\Locale::class, Locale::class);
        $this->app->bind(\Webkul\Core\Contracts\Country::class, Country::class);
        $this->app->bind(\Webkul\Core\Contracts\CountryState::class, CountryState::class);
        $this->app->bind(\Webkul\Customer\Contracts\CustomerGroup::class, CustomerGroup::class);
        $this->app->bind(\Webkul\Tax\Contracts\TaxCategory::class, TaxCategory::class);
        $this->app->bind(\Webkul\Tax\Contracts\TaxRate::class, TaxRate::class);
    }
}
