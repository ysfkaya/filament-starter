<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Role::whereName(config('filament-shield.super_admin.role_name'))->exists()) {
            Role::create([
                'name' => config('filament-shield.super_admin.role_name'),
                'guard_name' => config('filament.auth.guard'),
            ]);
        }

        if (config('filament-shield.filament_user.enabled') && ! Role::whereName(config('filament-shield.filament_user.role_name'))->exists()) {
            Role::create([
                'name' => config('filament-shield.filament_user.role_name'),
                'guard_name' => config('filament.auth.guard'),
            ]);
        }

        Artisan::call('shield:generate');

        $admin = Admin::firstOrCreate(['email' => 'developer@site.com'], [
            'name' => 'Developer',
            'password' => bcrypt('123Developer.'),
        ]);

        $admin->assignRole(config('filament-shield.super_admin.role_name'));

        $owner = Admin::firstOrCreate(['email' => 'owner@site.com'], [
            'name' => 'Owner',
            'password' => bcrypt('123Owner.'),
        ]);

        $owner->assignRole(config('filament-shield.filament_user.role_name', config('filament-shield.super_admin.role_name')));
    }
}
