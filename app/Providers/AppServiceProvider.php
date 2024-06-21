<?php

namespace App\Providers;

use App\Services\CountryService;
use App\Services\CurrencyService;
use App\Repositories\CountryRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CurrencyRepository;
use App\Services\CountryServiceInterface;
use App\Services\CurrencyServiceInterface;
use App\Repositories\CountryRepositoryInterface;
use App\Repositories\CurrencyRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CountryServiceInterface::class, CountryService::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);

        $this->app->bind(CurrencyServiceInterface::class, CurrencyService::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);


    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
