1. [Requirements](#Requirements)
2. [Installation](#Installation)
3. [Usage](#Usage)
4. [Trait Improvements](#trait-improvements)
    1. [Authorization](#authorization)
    2. [Hide From Toolbar](#hide-from-toolbar)
    3. [With Related](#with-related)
    4. [Per Page](#per-page)
    5. [Decorate Collection](#decorate-collection)
    6. [Resource Link Parameters](#resource-link-parameters)
    7. [Create Link Parameters](#create-link-parameters)
    8. [Breadcrumbs](#breadcrumbs)
5. [Many Fields](#many-fields)
6. [Lens Field](#lens-field)

## Requirements

-   `php: ^7.4 | ^8`
-   `laravel/nova: ^4`

## Installation

```
composer require lupennat/nova-better-lens:^2.0
```

| NOVA    | PACKAGE |
| ------- | ------- |
| <4.29.5 | 1.x     |
| >4.29.6 | 2.x     |

## Usage

Laravel Nova Better Lens, improve [Nova Lenses](https://nova.laravel.com/docs/4.0/lenses/defining-lenses.html) Behaviours

```php
// in app/Nova/Resource.php

use Lupennat\BetterLens\ResolvesBetterLenses;

class User extends Resource
{
    use ResolvesBetterLenses;

    public function lenses(NovaRequest $request)
    {
        return [
            new Lenses\MostValuableUsers,
        ];
    }
}
```

Include trait on Lens.

```php

use Lupennat\BetterLens\BetterLens;

class MostValuableUsers extends Lens
{
    use BetterLens;
}
```

## Trait Improvements

### Authorization

Better Lens provide the ability to define authorization for lens. You can override parent resource methods using standard laravel authorization methods.

```php

use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;

class MostValuableUsers extends Lens
{
    use BetterLens;

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
```

#### Authorize To Create

By Default Nova Lens does not provide the ability to Create/Attach resource, to mantain compatibility `authorizedToCreate` will work only if lens will be loaded with many\* Fields or if the method is explicitly defined in the lens.

### Hide From Toolbar

Better Lens provide the ability to exclude a lens from parent resource toolbar list by the static method `hideFromToolbar` (by default is false).

```php

use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;

class MostValuableUsers extends Lens
{
    use BetterLens;

    /**
     * Hide lens from resource toolbar.
     *
     * @return bool
     */
    public static function hideFromToolbar() {
        return true;
    }

}
```

### With Related

Better Lens provide the ability to make lens Viewable only as related resource.\
By default all lens on Nova can be linked on Menu and can be loaded as a standalone index resource. With static method `withRelated`, you can override default behaviour and prevent lens to be loaded without a `viaResource` request (by default is empty array).

```php

use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;

class MostValuableUsers extends Lens
{
    use BetterLens;

    /**
     * Load lens only if viaResource request match resources.
     *
     * @return array<int, class-string<\Laravel\Nova\Resource>>
     */
    public static function withRelated() {
        return [Post::class, Video::class];
    }

}
```

### Per Page

Better Lens provide the ability to define `perPage` options for lens. Using static methods `perPageViaRelationship` and `perPageOptions` you can override parent resource `perPage` options (by default use parent resource options).

```php

use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;

class MostValuableUsers extends Lens
{
    use BetterLens;

    /**
     * The number of resources to show per page via relationships.
     *
     * @return int
     */
    public static function perPageViaRelationship()
    {
        return 5;
    }


    /**
     * The pagination per-page options configured for this resource.
     *
     * @return array<int, int>
     */
    public static function perPageOptions()
    {
        return [10, 20, 30];
    }

}
```

you can also choose to show pagination filter when lens are loaded as relation via static `showPaginationViaRelationship` method (by default is `false`).

```php

use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;

class MostValuableUsers extends Lens
{
    use BetterLens;

    /**
     * Show pagination filter when via relationship.
     *
     * @return bool
     */
    public static function showPaginationViaRelationship()
    {
        return true;
    }

}
```

### Decorate Collection

Better Lens provide the ability to manipulate the collection of models returned by the query, you can override the static method `decorateCollection` and change the collection before it will be used to serialization (by default it return the original collection).

> Returned Collection should always return a Collection of Eloquent models, returning other types can generate runtime errors.

```php

use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;

class MostValuableUsers extends Lens
{
    use BetterLens;

    /**
     * Decorate collection before returning data.
     *
     * @param \Illuminate\Support\Collection $models
     *
     * @return \Illuminate\Support\Collection
     */
    public static function decorateCollection(LensRequest $request, $models)
    {
        // do stuff ...
        return $models;
    }

}

```

### Resource Link Parameters

Better Lens provide the ability to define extra parameters for view and edit url. You can define a list of `parameter => value` using static method `resourceLinkParameters` (by Default is empty array).

```php
    /**
     * Url Extra Parameters.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array<string,string>
     */
    public static function resourceLinkParameters($model, LensRequest $request)
    {
        return [
            'name' => $model->name
        ];
    }
```

### Create Link Parameters

Better Lens provide the ability to define extra parameters for creation url. You can define a list of `parameter => value` using static method `createLinkParameters` (by Default is empty array).

```php
    /**
     * Creation Url Extra Parameters.
     *
     * @return array<string,string>
     */
    public static function createLinkParameters(NovaRequest $request)
    {
        return [
            'param' => 'value'
        ];
    }
```

### Breadcrumbs

Better Lens provide the ability to override default Breadcrumbs for Lens pages using method `breadcrumbs` (by Default Nova Lens page breadcrumbs are loaded).

```php
    /**
     * Page Breadcrumbs.
     *
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    public function breadcrumbs(LensRequest $request)
    {
        return Breadcrumbs::make([]);
    }
```

## Many Fields

Better Lens automatically enable a new method `lens` for all Many Relationship Fields:

-   BelongsToMany
-   HasMany
-   HasManyThrough
-   MorphedByMany
-   MorphMany
-   MorphToMany

many relationship will load the lens view instead of the main resource.

```php
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{

    public function fields(Request $request)
    {
        return [
            HasMany::make('User Post', 'posts', Post::class)->lens(MostValuablePosts::class)
        ];
    }
}
```

## Lens Field

Better Lens provide a new field `Lens` that can be used to load a resource lens without a "real many relationship".\
By the field `Lens` it is possible to create a related table without creating a Custom ResourceTool.

### Example

We want to load a table with all translation key and the related translation value for current website.
The table `translation_keys` does not have a real relation with the website resource and only contains all the translation keys. The table `translations` instead contains both the relation with key and website.\
We don't want to use directly the `translations` table because we will lost the key not translated for the current website, we need to start from `translation_keys` table and load the website value from the `translations` table if present.

```php
use Laravel\Nova\Http\Requests\NovaRequest;
use Lupennat\BetterLens\Fields\Lens;

class Website extends Resource
{

    public function fields(Request $request)
    {
        return [
           Lens::make(__('Translations'), TranslationKey::class, TranslationKeyForWebsite::class)->collapsedByDefault(),
        ];
    }
}

```

```php
use Lupennat\BetterLens\BetterLens;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class TranslationKeyForWebsite extends Lens
{
    use BetterLens;

    public function fields(Request $request)
    {
        return [
           Text::make(__('key'), 'key'),
           Text::make(__('Value'), 'value')
        ];
    }

    /**
     * Get the query builder / paginator for the table.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->addSelect([
                'value' => Translation::whereColumn('translation_keys.id', 'translation.translation_key_id')
                            ->where('translation.website_id', $request->viaResourceId)
            ])
        ));
    }
}

```
