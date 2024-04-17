<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $permissions = [
            'create course',
            'view course',
            'update course',
            'delete course',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        $teacherRole = Role::create([
            'name' => 'teacher'
        ]);

        $teacherRole->givePermissionTo([
            'create course',
            'view course',
            'update course',
            'delete course',
        ]);

        $studentRole = Role::create([
            'name' => 'student'
        ]);

        $studentRole->givePermissionTo([
            'view course',
        ]);

        $user = User::create([
            'name' => 'Teddi',
            'email' => 'teddi@gascpns.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole($teacherRole);
    }
}
