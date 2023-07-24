<?php

namespace Lupennat\BetterLens;

use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * @method static bool authorizedToCreate(\Illuminate\Http\Request $request)
 * @method        bool authorizedToView(\Illuminate\Http\Request $request)
 * @method        bool authorizedToReplicate(\Illuminate\Http\Request $request)
 * @method        bool authorizedToUpdate(\Illuminate\Http\Request $request)
 * @method        bool authorizedToDelete(\Illuminate\Http\Request $request)
 * @method        bool authorizedToRestore(\Illuminate\Http\Request $request)
 * @method        bool authorizedToForceDelete(\Illuminate\Http\Request $request)
 * @method        bool authorizedToImpersonate(\Laravel\Nova\Http\Requests\NovaRequest $request)
 */
trait Authorizable
{
    /**
     * Determine if the resource may be updated, factoring in attachments.
     *
     * @return bool
     */
    public function authorizedToUpdateForSerialization(NovaRequest $request)
    {
        if ($request->viaManyToMany()) {
            return $request->findParentResourceOrFail()->authorizedToAttach(
                $request,
                $this->model()
            );
        }

        return $this->authorizedToUpdate($request);
    }

    /**
     * Determine if the resource may be deleted, factoring in detachments.
     *
     * @return bool
     */
    public function authorizedToDeleteForSerialization(NovaRequest $request)
    {
        if ($request->viaManyToMany()) {
            return $request->findParentResourceOrFail()->authorizedToDetach(
                $request,
                $this->model(),
                $request->viaRelationship
            );
        }

        return $this->authorizedToDelete($request);
    }
}
