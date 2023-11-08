<?php

namespace SurazDott\TwoStep;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use SurazDott\TwoStep\Http\Middleware\TwoStepVerification;

class TwoStepVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'twostep');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->vendorPublish();
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $router->middlewareGroup('twostep', [TwoStepVerification::class]);
    }

    /**
     * Publish files.
     *
     * @return void
     */
    private function vendorPublish()
    {
        $publishTag = 'twostep';

        $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/auth'),
            ],
            $publishTag
        );
    }
}
