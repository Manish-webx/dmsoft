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

        $doctor3 = User::firstOrCreate(
            ['email' => 'nitin@example.com'],
            [
                'name' => 'Dr. Nitin Yadav',
                'password' => Hash::make('password'),
            ]
        );

        if ($doctorRole && !$doctor3->hasRole('Doctor')) {
            $doctor3->assignRole($doctorRole);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $doctor3 = User::where('email', 'nitin@example.com')->first();
        if ($doctor3) {
            $doctor3->delete();
        }
    }
};
