<?php

namespace Database\Seeders;

use App\Enums\PermissionName;
use App\Enums\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionName::values() as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Administrator — full access. Rather than assigning every
        // permission explicitly (which drifts as new permissions are
        // added), User::canAccessPanel() plus a `Gate::before` hook
        // (see AuthServiceProvider-equivalent below) treats this role as
        // "can do anything" at the gate level. It still gets every
        // permission assigned too, so `hasPermissionTo()` checks that
        // don't go through Gate::before (rare, but e.g. package internals)
        // still resolve correctly.
        $superAdmin = Role::firstOrCreate(['name' => RoleName::SuperAdministrator->value]);
        $superAdmin->syncPermissions(PermissionName::values());

        // Administrator — manages content and the taxonomy, plus users,
        // but not role/permission definitions themselves.
        $administrator = Role::firstOrCreate(['name' => RoleName::Administrator->value]);
        $administrator->syncPermissions([
            PermissionName::ViewArticles->value,
            PermissionName::CreateArticles->value,
            PermissionName::UpdateArticles->value,
            PermissionName::DeleteArticles->value,
            PermissionName::UpdateAnyArticle->value,
            PermissionName::DeleteAnyArticle->value,
            PermissionName::PublishArticles->value,
            PermissionName::ManageCategories->value,
            PermissionName::ManageTags->value,
            PermissionName::ManageMedia->value,
            PermissionName::ManageUsers->value,
            PermissionName::ManageSponsoredContent->value,
            PermissionName::ManageTeamMembers->value,
            PermissionName::ManageMagazines->value,
            
        ]);

        // Editor — can create, edit, and publish any content, and manage
        // the taxonomy, but doesn't manage users.
        $editor = Role::firstOrCreate(['name' => RoleName::Editor->value]);
        $editor->syncPermissions([
            PermissionName::ViewArticles->value,
            PermissionName::CreateArticles->value,
            PermissionName::UpdateArticles->value,
            PermissionName::DeleteArticles->value,
            PermissionName::UpdateAnyArticle->value,
            PermissionName::DeleteAnyArticle->value,
            PermissionName::PublishArticles->value,
            PermissionName::ManageCategories->value,
            PermissionName::ManageTags->value,
            PermissionName::ManageMedia->value,
            PermissionName::ManageSponsoredContent->value,
            PermissionName::ManageTeamMembers->value,
            PermissionName::ManageMagazines->value,
        ]);

        // Author — can create and manage their *own* content only
        // (enforced in ArticlePolicy via ownership, not by omitting
        // UpdateArticles/DeleteArticles here) and cannot publish: an
        // author's work goes to Pending Review for an editor to publish.
        $author = Role::firstOrCreate(['name' => RoleName::Author->value]);
        $author->syncPermissions([
            PermissionName::ViewArticles->value,
            PermissionName::CreateArticles->value,
            PermissionName::UpdateArticles->value,
            PermissionName::DeleteArticles->value,
            PermissionName::ManageMedia->value,
        ]);
    }
}
