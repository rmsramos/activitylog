# Spatie/Laravel-activitylog for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rmsramos/activitylog.svg?style=flat-square)](https://packagist.org/packages/rmsramos/activitylog)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rmsramos/activitylog/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rmsramos/activitylog/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rmsramos/activitylog.svg?style=flat-square)](https://packagist.org/packages/rmsramos/activitylog/stats)

This package provides a Filament resource that shows you all of the activity logs and detailed view of each log created using the `spatie/laravel-activitylog` package. It also provides a relationship manager for related models.


## Requirements

* Filament v3
* Spatie/Laravel-activitylog v4

## Installation

You can install the package via composer:

```bash
composer require rmsramos/activitylog
```

After that run the install command:
```bash
php artisan activitylog:install
```
This will publish the config & migrations from `spatie/laravel-activitylog`

You can manually publish the configuration file with:

```bash
php artisan vendor:publish --tag="activitylog-config"
```

This is the contents of the published config file:

```php
return [
    'resources' => [
        'label'                     => 'Activity Log',
        'plural_label'              => 'Activity Logs',
        'navigation_group'          => null,
        'navigation_icon'           => 'heroicon-o-shield-check',
        'navigation_sort'           => null,
        'navigation_count_badge'    => false,
        'resource'                  => \Rmsramos\Activitylog\Resources\ActivitylogResource::class,
    ],
    'datetime_format' => 'd/m/Y H:i:s',
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="activitylog-views"
```

## Usage
In your Panel ServiceProvider active the plugin `(App\Providers\Filament)`
```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make(),
        ]);
}
```

## Customising the ActivitylogResource

You can swap out the `ActivitylogResource` used by updating the `->resource()` value. Use this to create your own `CustomResource` class and extend the original at `\Rmsramos\Activitylog\Resources\ActivitylogResource::class`. This will allow you to customise everything such as the views, table, form and permissions. If you wish to change the resource on List and View page be sure to replace the `getPages` method on the new resource and create your own version of the `ListPage` and `ViewPage` classes to reference the custom `CustomResource`.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->resource(\Path\For\Your\CustomResource::class),
        ]);
}
```

## Customising label Resource

You can swap out the `Resource label` used by updating the `->label()` and `->pluralLabel()` value.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->label('Log')
                ->pluralLabel('Logs'),
        ]);
}
```

## Grouping resource navigation items

You can add a `Resource navigation group` updating the `->navigationGroup()` value.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->navigationGroup('Activity Log'),
        ]);
}
```

## Customising a resource navigation icon
You can swap out the `Resource navigation icon` used by updating the `->navigationIcon()` value.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->navigationIcon('heroicon-o-shield-check'),
        ]);
}
```

## Active a count badge
You can active `Count Badge` updating the `->navigationCountBadge()` value.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->navigationCountBadge(true),
        ]);
}
```

## Set navigation sort
You can set the `Resource navigation sort` used by updating the `->navigationSort()` value.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->navigationSort(3),
        ]);
}
```

## Full configuration
```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->resource(\Path\For\Your\CustomResource::class)
                ->label('Log')
                ->pluralLabel('Logs')
                ->navigationGroup('Activity Log')
                ->navigationIcon('heroicon-o-shield-check')
                ->navigationCountBadge(true)
                ->navigationSort(2),
        ]);
}
```

## Relationship manager

If you have a model that uses the `Spatie\Activitylog\Traits\LogsActivity` trait, you can add the `Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager` relationship manager to your Filament resource to display all of the activity logs that are performed on your model.

```php
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

public static function getRelations(): array
{
    return [
        ActivitylogRelationManager::class,
    ];
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rômulo Ramos](https://github.com/rmsramos)
- [Alex Justesen](https://github.com/alexjustesen)
- [z3d0x](https://github.com/z3d0x/filament-logger)
- [Spatie Activitylog Contributors](https://github.com/spatie/laravel-activitylog#credits) 
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
