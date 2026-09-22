<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageTeamMembers->value);
    }

    public function view(User $user, TeamMember $member): bool
    {
        return $user->can(PermissionName::ManageTeamMembers->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageTeamMembers->value);
    }

    public function update(User $user, TeamMember $member): bool
    {
        return $user->can(PermissionName::ManageTeamMembers->value);
    }

    public function delete(User $user, TeamMember $member): bool
    {
        return $user->can(PermissionName::ManageTeamMembers->value);
    }
}