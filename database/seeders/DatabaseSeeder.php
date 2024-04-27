<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'teddi@gmail.com',
            'role' => 'admin',
            'username' => 'administator',
            'password' => bcrypt('password')
        ]);

        // role seeder
        $this->call([
            RolePermissionSeeder::class,
            TypeSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
