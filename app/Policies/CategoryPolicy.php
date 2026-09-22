<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageCategories->value);
    }

    public function view(User $user, Category $category): bool
    {
        return $user->can(PermissionName::ManageCategories->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageCategories->value);
    }

    public function update(User $user, Category $category): bool
    {
        return $user->can(PermissionName::ManageCategories->value);
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->can(PermissionName::ManageCategories->value);
    }
}
