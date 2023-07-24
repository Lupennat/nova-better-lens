<?php

namespace Lupennat\BetterLens\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Resources\LensViewResource;
use Lupennat\BetterLens\Http\Requests\BetterLensRequest;

class BetterLensController extends Controller
{
    /**
     * Get the specified lens and its resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BetterLensRequest $request)
    {
        $res = LensViewResource::make()->toArray($request);

        $res['per_page_options'] = $request->perPageOptions();

        return response()->json($res);
    }
}
