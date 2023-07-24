<?php

namespace Lupennat\BetterLens\Fields;

use Laravel\Nova\Fields\MorphToMany;

/**
 * @method static static make(class-string<\Laravel\Nova\Lenses\Lens> $lens, mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class MorphToManyLens extends MorphToMany
{
    use WithLens;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'morph-to-many-lens-field';
}
