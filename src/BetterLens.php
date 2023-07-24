<?php

namespace Lupennat\BetterLens;

use Laravel\Nova\Http\Requests\LensRequest;

/**
 * @method static int        perPageViaRelationship()
 * @method static array<int, int> perPageOptions()
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
     * Hide lens from resource toolbar.
     *
     * @return bool
     */
    public static function hideFromToolbar()
    {
        return false;
    }

    /**
     * Detect if lens can show on resource toolbar.
     *
     * @return bool
     */
    public static function canShowOnToolbar()
    {
        return !static::hideFromToolbar();
    }

    /**
     * Load lens only if viaResource request match resources.
     *
     * @return array<int, class-string<\Laravel\Nova\Resource>>
     */
    public static function withRelated()
    {
        return [];
    }

    /**
     * Detect if lens can be shown.
     *
     * @param string|null $resourceKey
     *
     * @return bool
     */
    public static function canBeShownByRelated($resourceKey)
    {
        $withRelated = static::withRelated();

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
