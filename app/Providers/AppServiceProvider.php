<?php

namespace App\Providers;

use App\Domain\Repositories\ClientRepositoryInterface;
use App\Domain\Repositories\ContractRepositoryInterface;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Domain\Services\PricingService;
use App\Domain\Strategies\LoyaltyDiscount;
use App\Domain\Strategies\ProgressiveDiscount;
use App\Domain\Strategies\QuantityDiscount;
use App\Domain\Strategies\ServiceSurcharge;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Repositories\EloquentContractRepository;
use App\Infrastructure\Repositories\EloquentServiceRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : null,
        );
    }

    private function registerRepositories(): void
    {
        $this->app->bind(
            ContractRepositoryInterface::class,
            EloquentContractRepository::class
        );
        $this->app->bind(
            ClientRepositoryInterface::class,
            EloquentClientRepository::class
        );
        $this->app->bind(
            ServiceRepositoryInterface::class,
            EloquentServiceRepository::class
        );
    }

    private function registerServices(): void
    {
        $this->app->bind(PricingService::class, function ($app) {
            return new PricingService(
                new QuantityDiscount(),
                new LoyaltyDiscount(),
                new ServiceSurcharge(),
                new ProgressiveDiscount()
            );
        });
    }
}
