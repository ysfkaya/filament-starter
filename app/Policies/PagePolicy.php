<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class PagePolicy
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
        return $admin->can('view_any_page');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Model $model)
    {
        return $admin->can('view_page');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return $admin->can('create_page');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Model $model)
    {
        return $admin->can('update_page');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Model $model)
    {
        return $admin->can('delete_page');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(Admin $admin)
    {
        return $admin->can('delete_any_page');
    }

}
