<?php

namespace PhpuanJck\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use PhpuanJck\Middleware\ProfilerMiddleware;
use PhpuanJck\Services\TraceParser;
use PhpuanJck\Services\Profiler;

class PhpuanJckServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/phpuan-jck.php', 'phpuan-jck');

        $this->app->singleton(TraceParser::class, function ($app) {
            return new TraceParser();
        });

        $this->app->singleton(Profiler::class, function ($app) {
            return new Profiler($app->make(TraceParser::class));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/phpuan-jck.php' => config_path('phpuan-jck.php'),
        ], 'phpuan-jck-config');

        $this->publishes([
            __DIR__.'/../Database/Migrations' => database_path('migrations'),
        ], 'phpuan-jck-migrations');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/phpuan-jck'),
        ], 'phpuan-jck-assets');

        $this->loadViewsFrom(__DIR__.'/../resources/views/vendor/phpuan-jck', 'phpuan-jck');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \PhpuanJck\Commands\InstallCommand::class,
                \PhpuanJck\Commands\CleanupTraces::class,
            ]);
        }
    }
}
