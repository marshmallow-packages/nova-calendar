<?php

namespace Marshmallow\NovaCalendar;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

use Marshmallow\NovaCalendar\Http\Middleware\Authorize;
use Marshmallow\NovaCalendar\Console\Commands\CreateDefaultCalendarDataProvider;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([]);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateDefaultCalendarDataProvider::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/nova-calendar.php' => config_path('nova-calendar.php'),
        ], 'config');
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $printedUpdateHelper = false;
        foreach (config('nova-calendar', []) as $calendarKey => $calendarConfig) {
            if (php_sapi_name() == 'cli' && !is_array($calendarConfig)) {
                if (!$printedUpdateHelper) {
                    echo "\n\n\033[31m  WARNING: Your config/nova-calendar.php file does not seem to be updated for Nova Calendar v2.0 yet.\033[0m
   > Updating the config file for v2.0 is trivial, take a look at the Upgrade Guide at https://marshmallow.github.io\n\n";

                    $printedUpdateHelper = true;
                }
            } else if (is_array($calendarConfig)) {
                if (!isset($calendarConfig['uri'])) {
                    throw new \Exception("Missing calendar config option `uri` for calendar `$calendarKey` in config/nova-calendar.php");
                } else if (!strlen(trim($calendarConfig['uri']))) {
                    throw new \Exception("Empty calendar config option `uri` for calendar `$calendarKey` in config/nova-calendar.php");
                } else {
                    Nova::router(['nova', Authorize::class], $calendarConfig['uri'])
                        ->group(__DIR__ . '/../routes/inertia.php');

                    Route::middleware(['nova', Authorize::class])
                        ->prefix('nova-vendor/marshmallow/nova-calendar/' . $calendarConfig['uri'])
                        ->group(__DIR__ . '/../routes/api.php');
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}
}
