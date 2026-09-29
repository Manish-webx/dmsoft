<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $doctorRole = null;
        if (class_exists(Role::class)) {
            $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);
        }

        $doctor = User::firstOrCreate(
            ['email' => 'charmy@example.com'],
            [
                'name' => 'Dr. Charmy',
                'password' => Hash::make('password'),
            ]
        );

        if ($doctorRole && !$doctor->hasRole('Doctor')) {
            $doctor->assignRole($doctorRole);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $doctor = User::where('email', 'charmy@example.com')->first();
        if ($doctor) {
            $doctor->delete();
        }
    }
};
