<?php

namespace App\Providers;

use BezhanSalleh\FilamentShield\FilamentShieldServiceProvider as BaseFilamentShieldServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentShieldServiceProvider extends BaseFilamentShieldServiceProvider
{
    /**
     * Disable the default resources of FilamentShieldServiceProvider.
     *
     * @var array
     */
    protected array $resources = [];

    public function configurePackage(Package $package): void
    {
        $package->setBasePath(
            base_path('vendor/bezhansalleh/filament-shield/src')
        );

        parent::configurePackage($package);
    }
}
