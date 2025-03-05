<?php

namespace LeeMarkWood\BlizzardApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
//use LeeMarkWood\BlizzardApi\Commands\BlizzardApiCommand;

class BlizzardApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('blizzard-api')
            ->hasConfigFile();
//            ->hasViews()
//            ->hasMigration('create_blizzard_api_table')
//            ->hasCommand(BlizzardApiCommand::class);
    }
}
