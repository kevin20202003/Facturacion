<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Invoice;
use App\Observers\InvoiceObserver;
use App\Repositories\ClientRepositoryInterface;
use App\Repositories\EloquentClientRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\EloquentProductRepository;
use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\EloquentInvoiceRepository;
use App\Services\Tax\DefaultTaxStrategy;
use App\Services\Tax\TaxStrategyInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(ClientRepositoryInterface::class, EloquentClientRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, EloquentInvoiceRepository::class);

        // Default tax strategy binding (can be swapped for another strategy)
        $this->app->bind(TaxStrategyInterface::class, function ($app) {
            return new DefaultTaxStrategy(0.21);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Invoice::observe(InvoiceObserver::class);
    }
}
