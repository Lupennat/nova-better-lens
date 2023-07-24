<?php

namespace Lupennat\BetterLens\Fields;

use Laravel\Nova\Fields\BelongsToMany;

/**
 * @method static static make(class-string<\Laravel\Nova\Lenses\Lens> $lens, mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class BelongsToManyLens extends BelongsToMany
{
    use WithLens;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'belongs-to-many-lens-field';
}
