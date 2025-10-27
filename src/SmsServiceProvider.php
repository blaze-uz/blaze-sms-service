<?php

namespace Blaze\SmsService;

use Carbon\Laravel\ServiceProvider;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 **/
class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('sms', fn () => new SmsService());

        $this->mergeConfigFrom(__DIR__.'/../config/sms.php', 'sms');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sms-service');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../config/sms.php' => config_path('sms.php'),
        ], 'sms-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'sms-migrations');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/sms-service'),
        ], 'sms-translations');
    }
}
