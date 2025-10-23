<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Category\Repositories\CategoryRepository;

class BagistoInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Bagisto with default data';

    /**
     * Execute the console command.
     */
    public function handle(
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        ChannelRepository $channelRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->info('Installing Bagisto...');

        // Create root category
        $rootCategory = $categoryRepository->create([
            'name' => 'Root',
            'status' => 1,
            'position' => 1,
            'display_mode' => 'products_and_description',
        ]);

        // Create default locale
        $locale = $localeRepository->create([
            'code' => 'en',
            'name' => 'English',
        ]);

        // Create default currency
        $currency = $currencyRepository->create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
        ]);

        // Create default channel
        $channel = $channelRepository->create([
            'code' => 'default',
            'name' => 'Default',
            'currencies' => [$currency->id],
            'locales' => [$locale->id],
            'default_locale_id' => $locale->id,
            'base_currency_id' => $currency->id,
            'root_category_id' => $rootCategory->id,
        ]);

        $this->info('Bagisto has been installed successfully.');
    }
}
