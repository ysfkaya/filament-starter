<?php

namespace App\Overrides\Filament\Shield;

use BezhanSalleh\FilamentShield\Commands\MakeGenerateShieldCommand as BaseMakeGenerateShieldCommand;
use BezhanSalleh\FilamentShield\FilamentShield;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MakeGenerateShieldCommand extends BaseMakeGenerateShieldCommand
{
    public $signature = 'shield:generate
        {--E|exclude : Generate permissions w/o policies except those `exclude`d.}
        {--f|force : Overwrite existing files.}
    ';

    protected function generateForResources(array $resources): Collection
    {
        return  collect($resources)
            ->reduce(function ($entites, $resource) {
                $model = Str::before(Str::afterLast($resource, '\\'), 'Resource');
                $entites[$model] = $model;

                return $entites;
            }, collect())
            ->values()
            ->each(function ($entity) {
                $model = Str::of($entity);

                $generatePolicy = function () use ($model) {
                    $path = $this->generatePolicyPath($model);

                    if (is_file($path) && file_exists($path) && ! $this->option('force')) {
                        $this->warn("File `{$path}` already exists. Skipping...");

                        return;
                    }

                    $this->copyStubToApp('DefaultPolicy', $path, $this->generatePolicyStubVariables($model));
                };

                if (config('filament-shield.resources_generator_option') === 'policies_and_permissions') {
                    $generatePolicy();
                    FilamentShield::generateForResource($model);
                }

                if (config('filament-shield.resources_generator_option') === 'policies') {
                    $generatePolicy();
                }

                if (config('filament-shield.resources_generator_option') === 'permissions') {
                    FilamentShield::generateForResource($model);
                }
            });
    }
}
