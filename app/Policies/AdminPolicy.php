<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $admin)
    {
        return $admin->can('view_any_admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Admin $adminModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Admin $adminModel)
    {
        return ! $adminModel->hasAnyRole(Admin::superRoles()) && $admin->can('view_admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return $admin->can('create_admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Admin $adminModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Admin $adminModel)
    {
        return ! $adminModel->hasAnyRole(Admin::superRoles()) && $admin->can('update_admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Admin $adminModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Admin $adminModel)
    {
        if ($adminModel->id === $admin->id) {
            return false;
        }

        return ! $admin->hasAnyRole(Admin::superRoles()) && $admin->can('delete_admin');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(Admin $admin)
    {
        return $admin->can('delete_any_admin');
    }
}
