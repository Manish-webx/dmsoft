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
        // 1. Rename existing John Doe / Doctor John to Dr. Pooja Varshney so existing appointments/prescriptions don't break
        $existingDoctor = User::where('name', 'like', '%John%')->orWhere('email', 'doctor@example.com')->first();
        
        if ($existingDoctor) {
            $existingDoctor->update([
                'name' => 'Dr. Pooja Varshney',
                'email' => 'pooja@example.com',
            ]);
        } else {
            $existingDoctor = User::firstOrCreate(
                ['email' => 'pooja@example.com'],
                [
                    'name' => 'Dr. Pooja Varshney',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // Ensure role Doctor is assigned
        if (class_exists(Role::class)) {
            $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);
            if (!$existingDoctor->hasRole('Doctor')) {
                $existingDoctor->assignRole($doctorRole);
            }
        }

        // 2. Create Dr. Piyush Gupta
        $doctor2 = User::firstOrCreate(
            ['email' => 'piyush@example.com'],
            [
                'name' => 'Dr. Piyush Gupta',
                'password' => Hash::make('password'),
            ]
        );
        
        if (class_exists(Role::class) && isset($doctorRole)) {
            if (!$doctor2->hasRole('Doctor')) {
                $doctor2->assignRole($doctorRole);
            }
        }

        // 3. Remove any other users named John or Doe who have Doctor role
        User::where('name', 'like', '%John%')
            ->orWhere('name', 'like', '%Doe%')
            ->get()
            ->each(function ($user) {
                if ($user->hasRole('Doctor')) {
                    $user->removeRole('Doctor');
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
