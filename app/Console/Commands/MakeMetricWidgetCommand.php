<?php

namespace App\Console\Commands;

use Filament\Panel;
use Illuminate\Support\Arr;
use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Filament\Resources\Resource;
use Filament\Support\Commands\Concerns\CanValidateInput;
use Filament\Support\Commands\Concerns\CanManipulateFiles;

class MakeMetricWidgetCommand extends Command
{
    use CanManipulateFiles;
    use CanValidateInput;

    protected $description = 'Create a new Filament Metric widget class';

    protected $signature = 'make:metric-widget {name?} {--R|resource=} {--panel=} {--F|force}';

    public function handle(): int
    {
        $widget = (string) str($this->argument('name') ?? $this->askRequired('Name (e.g. `BlogPostsMetricWidget`)', 'name'))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $widgetClass = (string) str($widget)->afterLast('\\');
        $widgetNamespace = str($widget)->contains('\\') ?
            (string) str($widget)->beforeLast('\\') :
            '';

        $resource = null;
        $resourceClass = null;

        if (class_exists(Resource::class)) {
            $resourceInput = $this->option('resource') ?? $this->ask('(Optional) Resource (e.g. `BlogPostResource`)');

            if ($resourceInput !== null) {
                $resource = (string) str($resourceInput)
                    ->studly()
                    ->trim('/')
                    ->trim('\\')
                    ->trim(' ')
                    ->replace('/', '\\');

                if (!str($resource)->endsWith('Resource')) {
                    $resource .= 'Resource';
                }

                $resourceClass = (string) str($resource)
                    ->afterLast('\\');
            }
        }

        $panel = null;

        if (class_exists(Panel::class)) {
            $panel = $this->option('panel');

            if ($panel) {
                $panel = Filament::getPanel($panel);
            }

            if (!$panel) {
                $panels = Filament::getPanels();

                /** @var ?Panel $panel */
                $panel = $panels[$this->choice(
                    'Where would you like to create this?',
                    array_unique([
                        ...array_map(
                            fn(Panel $panel): string => "The [{$panel->getId()}] panel",
                            $panels,
                        ),
                        '' => '[App\\Livewire] alongside other Livewire components',
                    ]),
                )] ?? null;
            }
        }

        $path = null;
        $namespace = null;
        $resourcePath = null;
        $resourceNamespace = null;

        if (!$panel) {
            $path = app_path('Livewire/');
            $namespace = 'App\\Livewire';
        } elseif ($resource === null) {
            $widgetDirectories = $panel->getWidgetDirectories();
            $widgetNamespaces = $panel->getWidgetNamespaces();

            $namespace = (count($widgetNamespaces) > 1) ?
                $this->choice(
                    'Which namespace would you like to create this in?',
                    $widgetNamespaces,
                ) :
                (Arr::first($widgetNamespaces) ?? 'App\\Filament\\Widgets');
            $path = (count($widgetDirectories) > 1) ?
                $widgetDirectories[array_search($namespace, $widgetNamespaces)] :
                (Arr::first($widgetDirectories) ?? app_path('Filament/Widgets/'));
        } else {
            $resourceDirectories = $panel->getResourceDirectories();
            $resourceNamespaces = $panel->getResourceNamespaces();

            $resourceNamespace = (count($resourceNamespaces) > 1) ?
                $this->choice(
                    'Which namespace would you like to create this in?',
                    $resourceNamespaces,
                ) :
                (Arr::first($resourceNamespaces) ?? 'App\\Filament\\Resources');
            $resourcePath = (count($resourceDirectories) > 1) ?
                $resourceDirectories[array_search($resourceNamespace, $resourceNamespaces)] :
                (Arr::first($resourceDirectories) ?? app_path('Filament/Resources/'));
        }

        $path = (string) str($widget)
            ->prepend('/')
            ->prepend($resource === null ? $path : "{$resourcePath}\\{$resource}\\Widgets\\")
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        if (
            !$this->option('force') && $this->checkForCollision([
                $path,
            ])
        ) {
            return static::INVALID;
        }

        $this->copyStubToApp('MetricWidget', $path, [
            'class' => $widgetClass,
            'namespace' => filled($resource) ? "{$resourceNamespace}\\{$resource}\\Widgets" . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : $namespace . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
        ]);

        $this->components->info("Successfully created {$widget}!");

        if ($resource !== null) {
            $this->components->info("Make sure to register the widget in `{$resourceClass}::getWidgets()`, and then again in `getHeaderWidgets()` or `getFooterWidgets()` of any `{$resourceClass}` page.");
        }

        return static::SUCCESS;
    }
}
