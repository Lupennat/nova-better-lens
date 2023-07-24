<?php

namespace Lupennat\BetterLens;

use Laravel\Nova\Http\Requests\LensRequest;

/**
 * @method static int        perPageViaRelationship()
 * @method static bool       showPaginationViaRelationship()
 * @method static array<int, int> perPageOptions()
 * @method static bool       hideFromToolbar()
 * @method static array<int, class-string<\Laravel\Nova\Resource>> withRelated()
 */
trait BetterLens
{
    use Authorizable;

    /**
     * Decorate collection before returning data.
     *
     * @param \Illuminate\Support\Collection $models
     *
     * @return \Illuminate\Support\Collection
     */
    public static function decorateCollection(LensRequest $request, $models)
    {
        return $models;
    }

    /**
     * Detect if lens can show on resource toolbar.
     *
     * @return bool
     */
    public function canShowOnToolbar()
    {
        return method_exists($this, 'hideFromToolbar') ? !$this::hideFromToolbar() : true;
    }

    /**
     * Detect if lens can be shown.
     *
     * @param string|null $resourceKey
     *
     * @return bool
     */
    public function canBeShownByRelated($resourceKey)
    {
        $withRelated = method_exists($this, 'withRelated') ? $this::withRelated() : [];

        if (count($withRelated) === 0) {
            return true;
        }

        if (!$resourceKey) {
            return false;
        }

        return count(array_filter($withRelated, function ($resource) use ($resourceKey) {
            return $resource::uriKey() === $resourceKey;
        })) > 0;
    }

    /**
     * Url Extra Parameters.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array<string,string>
     */
    public static function resourceLinkParameters($model)
    {
        return [];
    }
}
