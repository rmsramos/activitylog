# ActivityLog

### Spatie/Laravel-activitylog for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rmsramos/activitylog.svg?style=flat-square)](https://packagist.org/packages/rmsramos/activitylog)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rmsramos/activitylog/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rmsramos/activitylog/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rmsramos/activitylog.svg?style=flat-square)](https://packagist.org/packages/rmsramos/activitylog/stats)

<div class="filament-hidden">

![Screenshot of Application Feature](https://raw.githubusercontent.com/rmsramos/activitylog/main/arts/cover.jpeg)

</div>

This package provides a Filament resource that shows you all of the activity logs and detailed view of each log created using the `spatie/laravel-activitylog` package. It also provides a relationship manager for related models.

## Requirements

-   Laravel v11
-   Filament v3
-   Spatie/Laravel-activitylog v4

## Languages Supported

ActivityLog Plugin is translated for :

-   🇧🇷 Brazilian Portuguese
-   🇺🇸 English
-   🇪🇸 Spanish
-   🇫🇷 French
-   🇮🇷 Persian
-   🇦🇪 Arabic
-   🇵🇹 Portuguese
-   🇮🇱 Hebrew

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

And run migrates

```bash
php artisan migrate
```

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
        'navigation_item'           => true,
        'navigation_group'          => null,
        'navigation_icon'           => 'heroicon-o-shield-check',
        'navigation_sort'           => null,
        'default_sort_column'       => 'id',
        'default_sort_direction'    => 'desc',
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

### Basic Spatie ActivityLog usage

In you `Model` add `Spatie\Activitylog\Traits\LogsActivity` trait, and configure `getActivitylogOption` function

For more configuration, Please review [Spatie Docs](https://spatie.be/docs/laravel-activitylog/v4)

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NewsItem extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
```

## Plugin usage

![Screenshot of Application Feature](https://raw.githubusercontent.com/rmsramos/activitylog/main/arts/resource.png)

In your Panel ServiceProvider `(App\Providers\Filament)` active the plugin

Add the `Rmsramos\Activitylog\ActivitylogPlugin` to your panel config

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

You can swap out the `ActivitylogResource` used by updating the `->resource()` value. Use this to create your own `CustomResource` class and extend the original at `\Rmsramos\Activitylog\Resources\ActivitylogResource::class`. This will allow you to customise everything such as the views, table, form and permissions.

> [!NOTE]
> If you wish to change the resource on List and View page be sure to replace the `getPages` method on the new resource and create your own version of the `ListPage` and `ViewPage` classes to reference the custom `CustomResource`.

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

## Displaying the resource in the navigation

You can enable or disable the `Resource navigation item` by updating the `->navigationItem()` value.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->navigationItem(false), // by default is true
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

## Authorization

If you would like to prevent certain users from accessing the logs resource, you should add a authorize callback in the `ActivitylogPlugin` chain.

```php
use Rmsramos\Activitylog\ActivitylogPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ActivitylogPlugin::make()
                ->authorize(
                    fn () => auth()->user()->id === 1
                ),
        ]);
}
```

### Role Policy

To ensure ActivitylogResource access via RolePolicy you would need to add the following to your AppServiceProvider:

```php
use App\Policies\ActivityPolicy;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Activity::class, ActivityPolicy::class);
    }
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
                ->navigationItem(true)
                ->navigationGroup('Activity Log')
                ->navigationIcon('heroicon-o-shield-check')
                ->navigationCountBadge(true)
                ->navigationSort(2)
                ->authorize(
                    fn () => auth()->user()->id === 1
                ),
        ]);
}
```

## Relationship manager

If you have a model that uses the `Spatie\Activitylog\Traits\LogsActivity` trait, you can add the `Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager` relationship manager to your Filament resource to display all of the activity logs that are performed on your model.
![Screenshot of Application Feature](https://raw.githubusercontent.com/rmsramos/activitylog/main/arts/relationManager.png)

```php
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

public static function getRelations(): array
{
    return [
        ActivitylogRelationManager::class,
    ];
}
```

## Timeline Action

![Screenshot of Application Feature](https://raw.githubusercontent.com/rmsramos/activitylog/main/arts/timeline.png)

To make viewing activity logs easier, you can use a custom action. In your UserResource in the table function, add the `ActivityLogTimelineTableAction`.

```php
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            ActivityLogTimelineTableAction::make('Activities'),
        ]);
}
```

you can pass a matrix with the relationships, remember to configure your `Models`.

```php
public static function table(Table $table): Table
{
    return $table
        ->actions([
            ActivityLogTimelineTableAction::make('Activities')
                ->withRelations(['profile', 'address']), //opcional
        ]);
}
```

You can configure the icons and colors, by default the `'heroicon-m-check'` icon and the `'primary'` color are used.

```php
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            ActivityLogTimelineTableAction::make('Activities')
                ->timelineIcons([
                    'created' => 'heroicon-m-check-badge',
                    'updated' => 'heroicon-m-pencil-square',
                ])
                ->timelineIconColors([
                    'created' => 'info',
                    'updated' => 'warning',
                ])
        ]);
}
```

You can limit the number of results in the query by passing a limit, by default the last 10 records are returned.

```php
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            ActivityLogTimelineTableAction::make('Activities')
                ->limit(30),
        ]);
}
```

## Full Timeline configuration

```php
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            ActivityLogTimelineTableAction::make('Activities')
                ->withRelations(['profile', 'address'])
                ->timelineIcons([
                    'created' => 'heroicon-m-check-badge',
                    'updated' => 'heroicon-m-pencil-square',
                ])
                ->timelineIconColors([
                    'created' => 'info',
                    'updated' => 'warning',
                ])
                ->limit(10),
        ]);
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Acknowledgements

Special acknowledgment goes to these remarkable tools and people (developers), the Activity Log plugin only exists due to the inspiration and at some point the use of these people's codes.

-   [Jay-Are Ocero](https://github.com/199ocero/activity-timeline)
-   [Alex Justesen](https://github.com/alexjustesen)
-   [z3d0x](https://github.com/z3d0x/filament-logger)
-   [Filament](https://github.com/filamentphp/filament)
-   [Spatie Activitylog Contributors](https://github.com/spatie/laravel-activitylog#credits)

## Credits

-   [Rômulo Ramos](https://github.com/rmsramos)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
