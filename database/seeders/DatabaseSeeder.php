<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create Permissions
        $permissions = [
            'manage-users',
            'view-all-link-groups',
            'manage-own-link-groups',
            'manage-own-components',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(['manage-own-link-groups', 'manage-own-components']);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@fourlink.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Test User
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user@fourlink.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('user');
    }
}
