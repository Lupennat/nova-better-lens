<?php

namespace Lupennat\BetterLens\Fields;

use Laravel\Nova\Fields\HasMany;

/**
 * @method static static make(class-string<\Laravel\Nova\Lenses\Lens> $lens, mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasManyLens extends HasMany
{
    use WithLens;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-many-lens-field';
}
