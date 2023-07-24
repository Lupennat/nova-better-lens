<?php

namespace Lupennat\BetterLens\Fields;

use Laravel\Nova\Fields\HasManyThrough;

/**
 * @method static static make(class-string<\Laravel\Nova\Lenses\Lens> $lens, mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasManyThroughLens extends HasManyThrough
{
    use WithBetterLens;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-many-through-lens-field';
}
