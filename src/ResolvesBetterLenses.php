<?php

namespace Lupennat\BetterLens;

use Laravel\Nova\Http\Requests\NovaRequest;

trait ResolvesBetterLenses
{
    /**
     * Get the lenses for the given request.
     *
     * @return \Illuminate\Support\Collection<int, \Laravel\Nova\Lenses\Lens>
     */
    public function resolveLenses(NovaRequest $request)
    {
        $lenses = parent::resolveLenses($request);

        return $lenses->filter(function ($lens) use ($request) {
            if ($request->path() === 'nova-api/' . static::uriKey() . '/lenses') {
                if (method_exists($lens, 'canShowOnToolbar')) {
                    return $lens::canShowOnToolbar();
                }
            }

            if (str_ends_with($request->path(), '/cards')) {
                return true;
            }

            if (method_exists($lens, 'canBeShownByRelated')) {
                return $lens::canBeShownByRelated($request->viaResource);
            }

            return true;
        });

        return $lenses;
    }
}
