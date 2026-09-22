<?php

namespace App\Enums;

/**
 * Every permission string used across policies and the seeder. Keeping
 * these as enum cases (rather than raw strings scattered through the
 * codebase) means a typo in a policy check fails at parse time, not at
 * "why can't the editor publish anything" o'clock.
 */
enum PermissionName: string
{
    // Articles
    case ViewArticles = 'view_articles';
    case CreateArticles = 'create_articles';
    case UpdateArticles = 'update_articles';
    case DeleteArticles = 'delete_articles';
    case UpdateAnyArticle = 'update_any_article'; // edit others' articles, not just your own
    case DeleteAnyArticle = 'delete_any_article';
    case PublishArticles = 'publish_articles';

    // Taxonomy
    case ManageCategories = 'manage_categories';
    case ManageTags = 'manage_tags';

    // Media
    case ManageMedia = 'manage_media';

    case ManageSponsoredContent = 'manage_sponsored_content';
    case ManageTeamMembers = 'manage_team_members';

    // Users & roles
    case ManageUsers = 'manage_users';
    case ManageRoles = 'manage_roles';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
