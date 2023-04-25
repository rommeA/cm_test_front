<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $permission = Permission::where('name', 'users.list')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        $permission = Permission::where('name', 'users.show')->first();
        return $user->hasRole($permission->roles) || $model?->id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $permission = Permission::where('name', 'users.create')->first();
        return $user->hasRole($permission->roles);
    }

    public function update(User $user, User $model = null): bool
    {
        $permission = Permission::where('name', 'users.update')->first();

        $is_applicant_draft = $user->is_applicant &&
            $user->application_form_status === (int)config('enums.application_form_status.draft');

        $result = $user->hasRole($permission->roles) ||
            $model?->id === $user->id && $is_applicant_draft;

        return $result;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        $permission = Permission::where('name', 'users.delete')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        $permission = Permission::where('name', 'users.delete')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can see comments.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function seeComment(User $user, User  $model = null)
    {
        $hrRole = Role::where('name', config('enums.roles.HR department.db_name'))->first();
        $hasRole = $hrRole and $user->hasRole($hrRole);
        return $hasRole or $user->id == $model?->id;
    }

    public function seeNotes(User $user, User  $model = null): bool
    {
        $permission = Permission::where('name', 'users.seeNotes')->first();
        return $user->hasRole($permission->roles);
    }

    public function createNotes(User $user, User  $model = null): bool
    {
        $permission = Permission::where('name', 'users.createNotes')->first();
        return $user->hasRole($permission->roles);
    }

    /**
     * Determine whether the user can edit references.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function editReferences(User $user, User  $model = null)
    {
        $permission = Permission::where('name', 'users.seeNotes')->first();
        return $user->hasRole($permission->roles) or $user->id === $model?->id;
    }

    /**
     * Determine whether the user can create references.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createReferences(User $user, User  $model = null)
    {
        $permission = Permission::where('name', 'users.seeNotes')->first();
        return $user->hasRole($permission->roles) or $user->id === $model?->id;
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function changeApplicantStatus(User $user, User $model)
    {
        $permission = Permission::where('name', 'users.changeApplicantStatus')->first();
        return $user->hasRole($permission->roles);
    }

    public function getCV(User $user, User $model): bool
    {
        $hrRole = Role::where('name', config('enums.roles.HR department.db_name'))->first();

        return $hrRole && $user->hasRole($hrRole);
    }

    public function archive(User $user, User $model = null): bool
    {
        $hrRole = Role::where('name', config('enums.roles.HR department.db_name'))->first();
        $hasHrRole = $hrRole && $user->hasRole($hrRole);

        return
            $hasHrRole
            && $user->id !== $model?->id
            && ! $model?->is_archive;
    }

    public function updateAvailableDate(User $user, User $model) {
        return $this->update($user, $model) || $user->id === $model->id;
    }
}
