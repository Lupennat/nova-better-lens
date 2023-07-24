<?php

namespace Lupennat\BetterLens\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Resources\LensViewResource;
use Laravel\Nova\Query\ApplySoftDeleteConstraint;

class BetterLensViewResource extends LensViewResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $lens = $this->authorizedLensForRequest($request);

        $query = $request->newSearchQuery();

        if ($request->resourceSoftDeletes()) {
            (new ApplySoftDeleteConstraint())->__invoke($query, $request->trashed);
        }

        $paginator = $lens->query($request, $query);

        if ($paginator instanceof Builder || $paginator instanceof Relation) {
            $paginator = $paginator->simplePaginate($request->perPage());
        }

        return [
            'name' => $lens->name(),
            'resources' => $request->toResources($paginator->getCollection()),
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
            'per_page' => $paginator->perPage(),
            'per_page_options' => $request->perPageOptions(),
            'softDeletes' => $request->resourceSoftDeletes(),
            'hasId' => $lens->availableFields($request)->whereInstanceOf(ID::class)->isNotEmpty(),
            'polling' => $lens::$polling,
            'pollingInterval' => $lens::$pollingInterval * 1000,
            'showPollingToggle' => $lens::$showPollingToggle,
        ];
    }

    /**
     * Get authorized resource for the request.
     *
     * @return \Laravel\Nova\Lenses\Lens
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizedLensForRequest(LensRequest $request)
    {
        return $request->lens();
    }
}
