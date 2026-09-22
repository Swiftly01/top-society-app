<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\Article;
use App\Models\User;

/**
 * Super Administrators bypass all of this via the Gate::before hook in
 * AppServiceProvider — nothing here needs to special-case that role.
 *
 * The Author role's permission set (see RolesAndPermissionsSeeder) grants
 * `update_articles`/`delete_articles` but *not* `update_any_article`/
 * `delete_any_article` — so an Author can edit their own drafts but this
 * policy still blocks them from touching someone else's. Editors and
 * Administrators hold the "any" permissions and can touch everything.
 */
class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::ViewArticles->value);
    }

    public function view(User $user, Article $article): bool
    {
        return $user->can(PermissionName::ViewArticles->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::CreateArticles->value);
    }

    public function update(User $user, Article $article): bool
    {
        if ($user->can(PermissionName::UpdateAnyArticle->value)) {
            return true;
        }

        return $user->can(PermissionName::UpdateArticles->value) && $article->author_id === $user->id;
    }

    public function delete(User $user, Article $article): bool
    {
        if ($user->can(PermissionName::DeleteAnyArticle->value)) {
            return true;
        }

        return $user->can(PermissionName::DeleteArticles->value) && $article->author_id === $user->id;
    }

    public function restore(User $user, Article $article): bool
    {
        return $this->delete($user, $article);
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->can(PermissionName::DeleteAnyArticle->value);
    }

    /**
     * Not one of Filament's auto-checked abilities — called explicitly
     * from the Filament resource's Publish/Schedule actions via
     * `$user->can('publish', $article)`.
     */
    public function publish(User $user, Article $article): bool
    {
        return $user->can(PermissionName::PublishArticles->value);
    }
}
