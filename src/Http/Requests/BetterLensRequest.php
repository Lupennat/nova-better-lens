<?php

namespace Lupennat\BetterLens\Http\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\LensRequest;

/**
 * @property string|null $orderBy
 * @property string|null $orderByDirection
 */
class BetterLensRequest extends LensRequest
{
    /**
     * Map the given models to the appropriate resource for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toResources(Collection $models)
    {
        $resource = $this->resource();

        if (method_exists($this->lens(), 'decorateCollection')) {
            $models = $this->lens()->decorateCollection($this, $models);
        }

        return $models->map(function ($model) use ($resource) {
            $lensResource = $this->lens()->setResource($model);

            return transform((new $resource($model))->serializeForIndex(
                $this, $lensResource->resolveFields($this)
            ), function ($payload) use ($model, $lensResource) {
                $payload['resourceLinkParameters'] = method_exists($lensResource, 'resourceLinkParameters') ? $lensResource::resourceLinkParameters($model) : [];
                $payload['actions'] = collect(array_values($lensResource->actions($this)))
                    ->filter(function ($action) {
                        return $action->shownOnIndex() || $action->shownOnTableRow();
                    })
                    ->filter->authorizedToRun($this, $model)
                    ->values();

                if ($this->viaRelationship() && method_exists($lensResource, 'authorizedToCreate')) {
                    $payload['authorizedToCreate'] = $lensResource->authorizedToCreate($this);
                } else {
                    $payload['authorizedToCreate'] = false;
                }

                $payload['authorizedToCreate'] = false;

                if (method_exists($lensResource, 'authorizedToView')) {
                    $payload['authorizedToView'] = $lensResource->authorizedToView($this);
                }

                if (method_exists($lensResource, 'authorizedToReplicate')) {
                    $payload['authorizedToReplicate'] = $lensResource->authorizedToReplicate($this);
                }

                if (method_exists($lensResource, 'authorizedToUpdate')) {
                    $payload['authorizedToUpdate'] = $lensResource->authorizedToUpdateForSerialization($this);
                }

                if (method_exists($lensResource, 'authorizedToDelete')) {
                    $payload['authorizedToDelete'] = $lensResource->authorizedToDeleteForSerialization($this);
                }

                if (method_exists($lensResource, 'authorizedToRestore')) {
                    $payload['authorizedToRestore'] = $lensResource->authorizedToRestore($this);
                }

                if (method_exists($lensResource, 'authorizedToForceDelete')) {
                    $payload['authorizedToForceDelete'] = $lensResource->authorizedToForceDelete($this);
                }

                if (method_exists($lensResource, 'authorizedToImpersonate')) {
                    $payload['authorizedToImpersonate'] = $lensResource->authorizedToImpersonate($this);
                }

                return $payload;
            });
        });
    }

    /**
     * Get per page.
     *
     * @return int
     */
    public function perPage()
    {
        $perPageOptions = $this->perPageOptions();

        return (int) (in_array($this->perPage, $perPageOptions) ? $this->perPage : $perPageOptions[0]);
    }

    public function perPageOptions()
    {
        $resource = $this->resource();
        $lens = $this->lens();

        $perPageOptions = method_exists($lens, 'perPageOptions') ? $lens::perPageOptions() : $resource::perPageOptions();

        if ($this->viaResource && $this->viaResourceId && method_exists($lens, 'perPageViaRelationship')) {
            $perPageOptions[] = $lens->perPageViaRelationship();
            sort($perPageOptions);
        }

        return $perPageOptions;
    }
}
