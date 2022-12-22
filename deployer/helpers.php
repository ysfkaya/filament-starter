<?php

namespace Deployer;

task('artisan:sitemap:generate', artisan('sitemap:generate'));
task('artisan:responsecache:clear', artisan('responsecache:clear'));

task('yarn:build', function () {
    run('cd {{release_path}} && yarn build && yarn build:filament');
});
