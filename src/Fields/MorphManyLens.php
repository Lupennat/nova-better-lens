<?php

namespace Lupennat\BetterLens\Fields;

/**
 * @method static static make(class-string<\Laravel\Nova\Lenses\Lens> $lens, mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class MorphedByMany extends HasManyLens
{
    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType()
    {
        return 'morphMany';
    }

}
