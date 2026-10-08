<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\Advert;
use App\Models\User;

class AdvertPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageAdverts->value);
    }

    public function view(User $user, Advert $advert): bool
    {
        return $user->can(PermissionName::ManageAdverts->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageAdverts->value);
    }

    public function update(User $user, Advert $advert): bool
    {
        return $user->can(PermissionName::ManageAdverts->value);
    }

    public function delete(User $user, Advert $advert): bool
    {
        return $user->can(PermissionName::ManageAdverts->value);
    }
}
