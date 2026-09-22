<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Enums\RoleName;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageUsers->value);
    }

    public function view(User $user, User $model): bool
    {
        return $user->can(PermissionName::ManageUsers->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageUsers->value);
    }

    public function update(User $user, User $model): bool
    {
        return $user->can(PermissionName::ManageUsers->value);
    }

    public function delete(User $user, User $model): bool
    {
        // Guard rails: nobody deletes themselves, and Administrators (who
        // hold manage_users but not manage_roles) can't delete a Super
        // Administrator out from under the app.
        if ($user->id === $model->id) {
            return false;
        }

        if ($model->hasRole(RoleName::SuperAdministrator->value)) {
            return $user->can(PermissionName::ManageRoles->value);
        }

        return $user->can(PermissionName::ManageUsers->value);
    }

    /**
     * Not one of Filament's auto-checked abilities — called explicitly
     * wherever the role field is rendered, so an Administrator can edit a
     * user's name/email but not hand out a Super Administrator role.
     */
    public function assignRoles(User $user): bool
    {
        return $user->can(PermissionName::ManageRoles->value);
    }
}
