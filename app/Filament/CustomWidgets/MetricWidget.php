<?php

namespace App\Filament\CustomWidgets;

use Illuminate\Support\Str;
use Filament\Widgets\Widget;
use Filament\Support\Enums\IconPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\ComponentAttributeBag;

class MetricWidget extends Widget
{
    public ?string $filter = null;

    protected string|array|null $color = null;

    protected ?string $icon = null;

    protected string|Htmlable|null $description = null;

    protected ?string $descriptionIcon = null;

    protected IconPosition|string|null $descriptionIconPosition = null;

    /**
     * @var string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    protected string|array|null $descriptionColor = null;

    /**
     * @var array<string, scalar>
     */
    protected array $extraAttributes = [];

    protected bool $shouldOpenUrlInNewTab = false;

    protected ?string $url = null;

    protected ?string $id = null;

    protected string|Htmlable $label;

    public function color(string|array|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function icon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function description(string|Htmlable|null $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null  $color
     */
    public function descriptionColor(string|array|null $color): static
    {
        $this->descriptionColor = $color;

        return $this;
    }

    public function descriptionIcon(?string $icon, IconPosition|string|null $position = null): static
    {
        $this->descriptionIcon = $icon;
        $this->descriptionIconPosition = $position;

        return $this;
    }

    /**
     * @param  array<string, scalar>  $attributes
     */
    public function extraAttributes(array $attributes): static
    {
        $this->extraAttributes = $attributes;

        return $this;
    }

    public function openUrlInNewTab(bool $condition = true): static
    {
        $this->shouldOpenUrlInNewTab = $condition;

        return $this;
    }

    public function url(?string $url, bool $shouldOpenInNewTab = false): static
    {
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;
        $this->url = $url;

        return $this;
    }

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getColor(): string|array|null
    {
        return $this->color;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getDescription(): string|Htmlable|null
    {
        return $this->description;
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getDescriptionColor(): string|array|null
    {
        return $this->descriptionColor ?? $this->color;
    }

    public function getDescriptionIcon(): ?string
    {
        return $this->descriptionIcon;
    }

    public function getDescriptionIconPosition(): IconPosition|string
    {
        return $this->descriptionIconPosition ?? IconPosition::After;
    }

    /**
     * @return array<string, scalar>
     */
    public function getExtraAttributes(): array
    {
        return $this->extraAttributes;
    }

    public function getExtraAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraAttributes());
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function shouldOpenUrlInNewTab(): bool
    {
        return $this->shouldOpenUrlInNewTab;
    }

    public function getLabel(): string|Htmlable
    {
        return $this->label;
    }

    public function getId(): string
    {
        return $this->id ?? Str::slug($this->getLabel());
    }

    /**
     * @return scalar | Htmlable | Closure
     */
    public function getValue()
    {
        return null;
    }

    public function updatedFilter($value): void
    {
        $this->getValue();
    }

    protected function getFilters(): ?array
    {
        return null;
    }
}
