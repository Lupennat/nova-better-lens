<?php

namespace Lupennat\BetterLens\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;

trait WithLens
{
    /**
     * The class name of the related resource.
     *
     * @var class-string<\Laravel\Nova\Resource>
     */
    public $lensClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $lensName;

    /**
     * Create a new field.
     *
     * @param class-string<\Laravel\Nova\Lenses\Lens>   $lens
     * @param string                                    $name
     * @param string|null                               $attribute
     * @param class-string<\Laravel\Nova\Resource>|null $resource
     *
     * @return void
     */
    public function __construct($lens, $name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);
        $this->lensClass = $lens;
        $this->lensName = (new $lens())->uriKey();
    }

    /**
     * Determine if the field is to be shown on the detail view.
     */
    public function isShownOnDetail(NovaRequest $request, $resource): bool
    {
        if (method_exists($this->lensClass, 'canBeShownByRelated')) {
            return $this->lensClass::canBeShownByRelated($request->resource()::uriKey());
        }

        return parent::isShownOnDetail($request, $resource);
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return with(app(NovaRequest::class), function ($request) {
            return array_merge(parent::jsonSerialize(), [
                'lensName' => $this->lensName,
                'searchable' => $this->lensClass::searchable(),
                'createLinkParameters' => $this->lensClass::createLinkParameters($request),
                'createViaResource' => method_exists($this->lensClass, 'createViaResource') ? $this->lensClass::createViaResource($request) : null,
                'createViaResourceId' => method_exists($this->lensClass, 'createViaResourceId') ? $this->lensClass::createViaResourceId($request) : null,
                'createViaRelationship' => method_exists($this->lensClass, 'createViaRelationship') ? $this->lensClass::createViaRelationship($request) : null,
                'createRelationshipType' => method_exists($this->lensClass, 'createRelationshipType') ? $this->lensClass::createRelationshipType($request) : null,
                'isAuthorizedToCreate' => method_exists($this->lensClass, 'authorizedToCreate') ? $this->lensClass::authorizedToCreate($request) : $this->resourceClass::authorizedToCreate($request),
                'perPage' => method_exists($this->lensClass, 'perPageViaRelationship') ? $this->lensClass::perPageViaRelationship() : $this->resourceClass::$perPageViaRelationship,
                'showPagination' => method_exists($this->lensClass, 'showPaginationViaRelationship') ? $this->lensClass::showPaginationViaRelationship() : false,
            ]);
        });
    }
}
