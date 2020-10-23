<?php

namespace Alexmg86\LaravelGoodsru;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    private const CONFIG_PATH = __DIR__ . '/../config/laravel-goodsru.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('laravel-goodsru.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-goodsru'
        );

        $this->app->bind('laravel-goodsru', function () {
            return new LaravelGoodsru();
        });
    }
}
