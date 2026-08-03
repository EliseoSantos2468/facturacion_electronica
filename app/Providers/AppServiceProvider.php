<?php

namespace App\Providers;

use App\Services\Dte\SchemaValidator;
use App\Services\Mh\FirmadorClient;
use App\Services\Mh\MhClient;
use App\Services\Mh\TokenManager;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SchemaValidator::class, function () {
            return new SchemaValidator(resource_path('schemas/mh'));
        });

        $this->app->singleton(TokenManager::class, function ($app) {
            $config = $app['config']->get('mh');
            $store = $config['token_cache']['store'] ?? null;
            /** @var CacheRepository $cache */
            $cache = $store ? Cache::store($store) : Cache::store();

            return new TokenManager($app->make(HttpFactory::class), $cache, $config);
        });

        $this->app->singleton(FirmadorClient::class, function ($app) {
            return new FirmadorClient(
                $app->make(HttpFactory::class),
                $app['config']->get('mh.firmador'),
            );
        });

        $this->app->singleton(MhClient::class, function ($app) {
            return new MhClient(
                $app->make(HttpFactory::class),
                $app->make(TokenManager::class),
                $app['config']->get('mh'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
