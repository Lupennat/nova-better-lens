<?php

namespace Lupennat\BetterLens\Http\Controllers;

use Illuminate\Routing\Controller;
use Lupennat\BetterLens\Http\Requests\BetterLensRequest;
use Lupennat\BetterLens\Http\Resources\BetterLensViewResource;

class BetterLensController extends Controller
{
    /**
     * Get the specified lens and its resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BetterLensRequest $request)
    {
        $res = BetterLensViewResource::make()->toArray($request);

        return response()->json($res);
    }
}
