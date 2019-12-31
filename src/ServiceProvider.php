<?php

namespace Pace\AccessTelemetry;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Pace\AccessTelemetry\Console\PruneCommand;
use Pace\AccessTelemetry\Events\RequestLoginEvent;
use Pace\AccessTelemetry\Listeners\RecordRequestDetails;

class ServiceProvider extends IlluminateServiceProvider
{
    protected $listen = [
        RequestLoginEvent::class => [
            RecordRequestDetails::class,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerCommands();
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (!\class_exists('CreateAccessLogsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_access_logs_tables.php.stub' => database_path('migrations/' . \date('Y_m_d_His', \time()) . '_create_access_logs_tables.php'),
                ], 'migrations');
            }

            $this->configure();
        }

        $this->app->booted(function () {
            $config = config('access-telemtry');
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('access-logs:prune')->dailyAt($config['at']);
        });
        // $this->registerRoutes();
    }

    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([PruneCommand::class]);
        }
    }

    /**
     * Register the Access Telemetry routes.
     */
    protected function registerRoutes()
    {
        $authConfig = $this->app['config']->get('access-telemetry.auth-route', []);
        $namespace  = ['namespace'=> 'Pace\AccessTelemetry\Http\Controllers'];

        Route::group(\array_merge($config, $namespace), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });

        // Route::group(\array_merge($authConfig, $namespace), function () {
        //     $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        // });
    }

    /**
     * Setup the configuration for Horizon.
     */
    protected function configure()
    {
        $this->publishes([
            __DIR__ . '/../config/access-telemetry.php' => config_path('access-telemetry.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/access-telemetry.php',
            'access-telemetry'
        );
    }
}
