<?php

namespace Rmsramos\Activitylog;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Carbon;

class ActivitylogPlugin implements Plugin
{
    use EvaluatesClosures;

    protected ?string $resource = null;

    protected string|Closure|null $label = null;

    protected string|Closure|null $resourceActionLabel = null;

    protected bool|Closure $isResourceActionHidden = false;

    protected bool|Closure $isRestoreActionHidden = false;

    protected bool|Closure $isRestoreModelActionHidden = true;

    protected Closure|bool $navigationItem = true;

    protected string|Closure|null $navigationGroup = null;

    protected string|Closure|null $dateParser = null;

    protected string|Closure|null $dateFormat = null;

    protected string|Closure|null $datetimeFormat = null;

    protected ?Closure $datetimeColumnCallback = null;

    protected ?Closure $datePickerCallback = null;

    protected ?Closure $translateSubject = null;

    protected ?Closure $translateLogKey = null;

    protected ?string $navigationIcon = null;

    protected ?int $navigationSort = null;

    protected ?bool $navigationCountBadge = null;

    protected string|Closure|null $pluralLabel = null;

    protected bool|Closure $authorizeUsing = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'rmsramos/activitylog';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                $this->getResource(),
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getResource(): string
    {
        return $this->resource ?? config('filament-activitylog.resources.resource');
    }

    public function getLabel(): string
    {
        return $this->evaluate($this->label) ?? config('filament-activitylog.resources.label');
    }

    public function getResourceActionLabel(): string
    {
        return $this->evaluate($this->resourceActionLabel) ?? config('filament-activitylog.resources.resource_action_label');
    }

    public function getIsResourceActionHidden(): bool
    {
        return $this->evaluate($this->isResourceActionHidden) ?? config('filament-activitylog.resources.hide_resource_action');
    }

    public function getIsRestoreActionHidden(): bool
    {
        return $this->evaluate($this->isRestoreActionHidden) ?? config('filament-activitylog.resources.hide_restore_action');
    }

    public function getIsRestoreModelActionHidden(): bool
    {
        return $this->evaluate($this->isRestoreModelActionHidden) ?? config('filament-activitylog.resources.hide_restore_model_action');
    }

    public function getPluralLabel(): string
    {
        return $this->evaluate($this->pluralLabel) ?? config('filament-activitylog.resources.plural_label');
    }

    public function getNavigationItem(): bool
    {
        return $this->evaluate($this->navigationItem) ?? config('filament-activitylog.resources.navigation_item');
    }

    public function getNavigationGroup(): ?string
    {
        return $this->evaluate($this->navigationGroup) ?? config('filament-activitylog.resources.navigation_group');
    }

    public function getDateFormat(): ?string
    {
        return $this->evaluate($this->dateFormat) ?? config('filament-activitylog.date_format');
    }

    public function getDatetimeFormat(): ?string
    {
        return $this->evaluate($this->datetimeFormat) ?? config('filament-activitylog.datetime_format');
    }

    public function getDatetimeColumnCallback(): ?Closure
    {
        return $this->datetimeColumnCallback;
    }

    public function getDatePickerCallback(): ?Closure
    {
        return $this->datePickerCallback;
    }

    public function getTranslateSubject($label): ?string
    {
        if (is_null($this->translateSubject)) {
            return $label;
        }

        $callable = $this->translateSubject;

        return $callable($label);
    }

    public function getTranslateLogKey($label): ?string
    {
        if (is_null($this->translateLogKey)) {
            return $label;
        }

        $callable = $this->translateLogKey;

        return $callable($label);
    }

    public function getDateParser(): ?Closure
    {
        return $this->dateParser ?? fn ($date) => Carbon::parse($date);
    }

    public function getNavigationIcon(): ?string
    {
        return $this->navigationIcon ?? config('filament-activitylog.resources.navigation_icon');
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort ?? config('filament-activitylog.resources.navigation_sort');
    }

    public function getNavigationCountBadge(): ?bool
    {
        return $this->navigationCountBadge ?? config('filament-activitylog.resources.navigation_count_badge');
    }

    public function resource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function label(string|Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function resourceActionLabel(string|Closure $label): static
    {
        $this->resourceActionLabel = $label;

        return $this;
    }

    public function isResourceActionHidden(bool|Closure $isHidden): static
    {
        $this->isResourceActionHidden = $isHidden;

        return $this;
    }

    public function isRestoreActionHidden(bool|Closure $isHidden): static
    {
        $this->isRestoreActionHidden = $isHidden;

        return $this;
    }

    public function isRestoreModelActionHidden(bool|Closure $isHidden): static
    {
        $this->isRestoreModelActionHidden = $isHidden;

        return $this;
    }

    public function pluralLabel(string|Closure $label): static
    {
        $this->pluralLabel = $label;

        return $this;
    }

    public function navigationItem(Closure|bool $value = true): static
    {
        $this->navigationItem = $value;

        return $this;
    }

    public function navigationGroup(string|Closure|null $group = null): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function dateParser(?Closure $parser = null): static
    {
        $this->dateParser = $parser;

        return $this;
    }

    public function dateFormat(string|Closure|null $format = null): static
    {
        $this->dateFormat = $format;

        return $this;
    }

    public function datetimeFormat(string|Closure|null $format = null): static
    {
        $this->datetimeFormat = $format;

        return $this;
    }

    public function customizeDatetimeColumn(Closure $callable): self
    {
        $this->datetimeColumnCallback = $callable;

        return $this;
    }

    public function customizeDatePicker(Closure $callable): self
    {
        $this->datePickerCallback = $callable;

        return $this;
    }

    public function translateSubject(string|Closure|null $callable = null): static
    {
        $this->translateSubject = $callable;

        return $this;
    }

    public function translateLogKey(string|Closure|null $callable = null): static
    {
        $this->translateLogKey = $callable;

        return $this;
    }

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationSort(int $order): static
    {
        $this->navigationSort = $order;

        return $this;
    }

    public function navigationCountBadge(bool $show = true): static
    {
        $this->navigationCountBadge = $show;

        return $this;
    }

    public function authorize(bool|Closure $callback = true): static
    {
        $this->authorizeUsing = $callback;

        return $this;
    }

    public function isAuthorized(): bool
    {
        return $this->evaluate($this->authorizeUsing) === true;
    }
}
