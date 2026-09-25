<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use App\Models\User;

class NewsletterEditionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ManageNewsletters->value);
    }

    public function view(User $user, NewsletterEdition $newsletter): bool
    {
        return $user->can(PermissionName::ManageNewsletters->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::ManageNewsletters->value);
    }

    public function update(User $user, NewsletterEdition $newsletter): bool
    {
        return $user->can(PermissionName::ManageNewsletters->value);
    }

    public function delete(User $user, NewsletterEdition $newsletter): bool
    {
        return $user->can(PermissionName::ManageNewsletters->value);
    }
}