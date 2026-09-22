<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\SponsoredFeature;
use App\Models\User;

class SponsoredFeaturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageSponsoredContent->value);
    }

    public function view(User $user, SponsoredFeature $feature): bool
    {
        return $user->can(PermissionName::ManageSponsoredContent->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageSponsoredContent->value);
    }

    public function update(User $user, SponsoredFeature $feature): bool
    {
        return $user->can(PermissionName::ManageSponsoredContent->value);
    }

    public function delete(User $user, SponsoredFeature $feature): bool
    {
        return $user->can(PermissionName::ManageSponsoredContent->value);
    }
}
