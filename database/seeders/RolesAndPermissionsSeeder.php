<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);
        $receptionistRole = Role::firstOrCreate(['name' => 'Receptionist']);

        // Create an Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Create Doctor users
        $doctor1 = User::firstOrCreate(
            ['email' => 'pooja@example.com'],
            [
                'name' => 'Dr. Pooja Varshney',
                'password' => Hash::make('password'),
            ]
        );
        $doctor1->assignRole($doctorRole);

        $doctor2 = User::firstOrCreate(
            ['email' => 'piyush@example.com'],
            [
                'name' => 'Dr. Piyush Gupta',
                'password' => Hash::make('password'),
            ]
        );
        $doctor2->assignRole($doctorRole);

        // Create a Receptionist user
        $receptionistUser = User::firstOrCreate(
            ['email' => 'receptionist@example.com'],
            [
                'name' => 'Receptionist Jane',
                'password' => Hash::make('password'),
            ]
        );
        $receptionistUser->assignRole($receptionistRole);
    }
}
