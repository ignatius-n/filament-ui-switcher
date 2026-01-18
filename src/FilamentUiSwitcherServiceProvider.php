<?php

namespace Andreia\FilamentUiSwitcher;

use Andreia\FilamentUiSwitcher\Livewire\UiPreferences as UiPreferencesLivewire;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUiSwitcherServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-ui-switcher')
            ->hasConfigFile('ui-switcher')
            ->hasViews()
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        // This runs during the package registration phase
    }

    public function packageBooted(): void
    {
        // Register Livewire component
        Livewire::component('filament-ui-switcher', UiPreferencesLivewire::class);

        FilamentAsset::register([
            Css::make('ui-switcher-styles', __DIR__.'/../dist/ui-switcher.css'),
            Js::make('ui-switcher-scripts', __DIR__.'/../dist/ui-switcher.js'),
        ], package: 'andreia/filament-ui-switcher');
    }

    public function boot(): void
    {
        parent::boot();

        // Publishing migration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/add_ui_preferences_to_users_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_add_ui_preferences_to_users_table.php'),
            ], 'filament-ui-switcher-migrations');
        }
    }
}
