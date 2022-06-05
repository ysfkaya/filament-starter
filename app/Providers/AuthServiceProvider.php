<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability, $args) {
            $model = head($args);

            // We do not allow full authorization for
            // administrators even if they are super users.
            // Instead we check in Policy.
            if ($model instanceof Admin) {
                return null;
            }

            return ! $user instanceof Admin ? null : ($user->isSuper() ? true : null);
        });
    }
}
