<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\Magazine;
use App\Models\User;

class MagazinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageMagazines->value);
    }

    public function view(User $user, Magazine $magazine): bool
    {
        return $user->can(PermissionName::ManageMagazines->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageMagazines->value);
    }

    public function update(User $user, Magazine $magazine): bool
    {
        return $user->can(PermissionName::ManageMagazines->value);
    }

    public function delete(User $user, Magazine $magazine): bool
    {
        return $user->can(PermissionName::ManageMagazines->value);
    }
}
