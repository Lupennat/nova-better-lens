<?php

namespace Lupennat\BetterLens\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Menu\Breadcrumb;
use Laravel\Nova\Menu\Breadcrumbs;
use Laravel\Nova\Nova;
use Lupennat\BetterLens\Http\Requests\BetterLensRequest;
use Lupennat\BetterLens\Http\Resources\BetterLensViewResource;

class BetterLensController extends Controller
{
    /**
     * Show Resource Lens page using Inertia.
     *
     * @return \Inertia\Response
     */
    public function __invoke(BetterLensRequest $request)
    {
        $lens = BetterLensViewResource::make()->authorizedLensForRequest($request);

        return Inertia::render('Nova.BetterLens', [
            'breadcrumbs' => method_exists($lens, 'breadcrumbs') ? $lens->breadcrumbs($request) : $this->breadcrumbs($request, $lens),
            'resourceName' => $request->route('resource'),
            'lens' => $lens->uriKey(),
            'searchable' => $lens::searchable(),
            'viaResource' => $request->viaResource,
            'viaResourceId' => $request->viaResourceId,
            'isAuthorizedToCreate' => method_exists($lens, 'authorizedToCreate') ? $lens::authorizedToCreate($request) : false,
'createLinkParameters' => $lens::createLinkParameters($request),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    protected function breadcrumbs(LensRequest $request)
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
            Breadcrumb::make($request->lens()->name()),
        ]);
    }
}
