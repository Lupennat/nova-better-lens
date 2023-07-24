<?php

namespace Lupennat\BetterLens;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasManyThrough;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Nova;
use Lupennat\BetterLens\Fields\BelongsToManyLens;
use Lupennat\BetterLens\Fields\HasManyLens;
use Lupennat\BetterLens\Fields\HasManyThroughLens;
use Lupennat\BetterLens\Fields\MorphToManyLens;

class BetterLensServiceProvider extends ServiceProvider
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
            Nova::script('better-lens', __DIR__ . '/../dist/js/better-lens.js');
        });

        Field::macro('lens', function ($lens) {
            $field = $this;
            if ($this instanceof HasMany) {
                $field = HasManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            } elseif ($this instanceof BelongsToMany) {
                $field = BelongsToManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            } elseif ($this instanceof HasManyThrough) {
                $field = HasManyThroughLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            } elseif ($this instanceof MorphToMany) {
                $field = MorphToManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }

            return $field;
        });
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

        Route::middleware(['nova'])
            ->prefix('nova-vendor/better-lens')
            ->group(__DIR__ . '/../routes/api.php');

        Route::namespace('Lupennat\BetterLens\Http\Controllers')
            ->domain(config('nova.domain', null))
            ->middleware(config('nova.api_middleware', []))
            ->prefix(Nova::path())
            ->as('nova.pages.')
            ->group(function (Router $router) {
                $router->get('resources/{resource}/lens/{lens}', 'Pages\BetterLensController')->name('lens');
            });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
