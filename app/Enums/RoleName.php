<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * The four roles from the spec, in descending order of privilege. The
 * ->value of each case is exactly the role name stored by spatie/laravel-
 * permission (see RolesAndPermissionsSeeder), so `$user->hasRole(RoleName::Editor->value)`
 * (or the `HasRoleName` trait helper on User) always matches what's seeded.
 */
enum RoleName: string 
{
    case SuperAdministrator = 'super_administrator';
    case Administrator = 'administrator';
    case Editor = 'editor';
    case Author = 'author';

    public function getLabel(): string
    {
        return match ($this) {
            self::SuperAdministrator => 'Super Administrator',
            self::Administrator => 'Administrator',
            self::Editor => 'Editor',
            self::Author => 'Author',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->getLabel()])
            ->all();
    }
}
