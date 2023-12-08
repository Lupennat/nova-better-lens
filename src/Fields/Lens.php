<?php

namespace Lupennat\BetterLens\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Contracts\ListableField;
use Laravel\Nova\Contracts\RelatableField;
use Laravel\Nova\Fields\Collapsable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Lens extends Field implements ListableField, RelatableField
{
    use Collapsable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'lens-field';

    /**
     * The class name of the related resource.
     *
     * @var class-string<\Laravel\Nova\Resource>
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * The displayable singular label of the relation.
     *
     * @var string|null
     */
    public $singularLabel;

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
     * @param string                                  $name
     * @param class-string<\Laravel\Nova\Resource>    $resource
     * @param class-string<\Laravel\Nova\Lenses\Lens> $lens
     *
     * @return void
     */
    public function __construct($name, $resource, $lens)
    {
        parent::__construct($name, null, null);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);

        $this->resourceClass = $resource;
        $this->resourceName = $resource::uriKey();

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
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName()
    {
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType()
    {
    }

    /**
     * Determine if the field should be displayed for the given request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return call_user_func(
            [$this->resourceClass, 'authorizedToViewAny'],
            $request
        ) && parent::authorize($request);
    }

    /**
     * Resolve the field's value.
     *
     * @param string|null $attribute
     *
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
    }

    /**
     * Set the displayable singular label of the resource.
     *
     * @param string $singularLabel
     *
     * @return $this
     */
    public function singularLabel($singularLabel)
    {
        $this->singularLabel = $singularLabel;

        return $this;
    }

    /**
     * Make current field behaves as panel.
     *
     * @return \Laravel\Nova\Panel
     */
    public function asPanel()
    {
        return Panel::make($this->name, [$this])
            ->withMeta([
                'prefixComponent' => true,
            ])->withComponent('relationship-panel');
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
                'collapsable' => $this->collapsable,
                'collapsedByDefault' => $this->collapsedByDefault,
                'relatable' => false,
                'createLinkParameters' => $this->lensClass::createLinkParameters($request),
                'createViaResource' => method_exists($this->lensClass, 'createViaResource') ? $this->lensClass::createViaResource($request) : null,
                'createViaResourceId' => method_exists($this->lensClass, 'createViaResourceId') ? $this->lensClass::createViaResourceId($request) : null,
                'createViaRelationship' => method_exists($this->lensClass, 'createViaRelationship') ? $this->lensClass::createViaRelationship($request) : null,
                'createRelationshipType' => method_exists($this->lensClass, 'createRelationshipType') ? $this->lensClass::createRelationshipType($request) : null,
                'isAuthorizedToCreate' => method_exists($this->lensClass, 'authorizedToCreate') ? $this->lensClass::authorizedToCreate($request) : false,
                'perPage' => method_exists($this->lensClass, 'perPageViaRelationship') ? $this->lensClass::perPageViaRelationship() : $this->resourceClass::$perPageViaRelationship,
                'showPagination' => method_exists($this->lensClass, 'showPaginationViaRelationship') ? $this->lensClass::showPaginationViaRelationship() : false,
                'resourceName' => $this->resourceName,
                'singularLabel' => $this->singularLabel ?? $this->resourceClass::singularLabel(),
                'lensName' => $this->lensName,
                'searchable' => $this->lensClass::searchable(),
            ]);
        });
    }
}
