<?php

namespace App\Providers;

use App\Support\ResilientGeminiProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Prism\Prism\PrismManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('files', fn () => new WindowsSafeFilesystem());
        $this->app->alias('files', Filesystem::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(PrismManager::class)->extend('gemini', function ($app, array $config) {
            $retryTimes = (int) ($config['retry_times'] ?? 0);
            $retrySleepMs = (int) ($config['retry_sleep_ms'] ?? 0);

            return new ResilientGeminiProvider(
                apiKey: $config['api_key'] ?? $config['key'] ?? '',
                url: $config['url'] ?? 'https://generativelanguage.googleapis.com/v1beta/models',
                defaultClientOptions: array_filter([
                    'verify' => $config['verify'] ?? config('ai.providers.gemini.verify'),
                    'timeout' => isset($config['timeout']) ? (float) $config['timeout'] : null,
                    'connect_timeout' => isset($config['connect_timeout']) ? (float) $config['connect_timeout'] : null,
                ], fn ($value) => $value !== null),
                defaultRetry: $retryTimes > 0
                    ? [$retryTimes, max(100, $retrySleepMs)]
                    : [],
            );
        });
    }
}
