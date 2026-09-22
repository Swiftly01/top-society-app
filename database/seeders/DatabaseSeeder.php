<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);

        // User::factory()->create([
        //     'name' => 'Sad User',
        //     'email' => 'sad@example.com',
        //     'password' => '123456789'
        // ]);

        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'demo@example.com',
            'password' => '123456789'
        ]);

        $admin->assignRole(RoleName::SuperAdministrator->value);
    }
}
