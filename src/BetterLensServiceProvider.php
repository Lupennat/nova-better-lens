<?php

namespace Lupennat\BetterLens;

use Laravel\Nova\Nova;
use Illuminate\Routing\Router;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\MorphToMany;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\MorphedByMany;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Fields\HasManyThrough;
use Lupennat\BetterLens\Fields\HasManyLens;
use Lupennat\BetterLens\Fields\MorphManyLens;
use Lupennat\BetterLens\Fields\MorphToManyLens;
use Lupennat\BetterLens\Fields\BelongsToManyLens;
use Lupennat\BetterLens\Fields\MorphedByManyLens;
use Lupennat\BetterLens\Fields\HasManyThroughLens;

class BetterLensServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('better-lens-v2', __DIR__.'/../dist/js/better-lens.js');
        });

        Field::macro('lens', function ($lens) {
            $field = $this;

            if ($this instanceof BelongsToMany) {
                return BelongsToManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }

            if ($this instanceof HasManyThrough) {
                return HasManyThroughLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }

            if ($this instanceof MorphToMany) {
                return MorphToManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }

            if ($this instanceof MorphedByMany) {
                return MorphedByManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }

            if ($this instanceof MorphMany) {
                return MorphManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }

            if ($this instanceof HasMany) {
                return HasManyLens::make($lens, $this->name, $this->attribute, $this->resourceClass);
            }
            return $field;
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(config('nova.api_middleware', []))
             ->prefix('nova-vendor/better-lens')
             ->group(__DIR__.'/../routes/api.php');

        Route::namespace('Lupennat\BetterLens\Http\Controllers')
             ->domain(config('nova.domain', null))
             ->middleware(config('nova.api_middleware', []))
             ->prefix(Nova::path())
             ->as('nova.pages.')
             ->group(function (Router $router) {
                 $router->get('resources/{resource}/lens/{lens}', 'Pages\BetterLensController')
                        ->name('lens');
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
