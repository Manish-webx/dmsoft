<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Doctor Users (if not exists)
        $doctor1 = User::firstOrCreate(
            ['email' => 'pooja@example.com'],
            [
                'name' => 'Dr. Pooja Varshney',
                'password' => Hash::make('password')
            ]
        );
        if (!$doctor1->hasRole('Doctor')) {
            $doctor1->assignRole('Doctor');
        }

        $doctor2 = User::firstOrCreate(
            ['email' => 'piyush@example.com'],
            [
                'name' => 'Dr. Piyush Gupta',
                'password' => Hash::make('password')
            ]
        );
        if (!$doctor2->hasRole('Doctor')) {
            $doctor2->assignRole('Doctor');
        }

        $doctors = [$doctor1, $doctor2];

        // 2. Create Patients
        $patients = [];
        for ($i = 1; $i <= 5; $i++) {
            $patients[] = Patient::create([
                'name' => "Patient $i",
                'phone' => "123456789$i",
                'gender' => $i % 2 == 0 ? 'Female' : 'Male',
                'dob' => Carbon::now()->subYears(20 + $i)->format('Y-m-d'),
                'address' => "Address $i, City",
                'notes' => "Notes for patient $i"
            ]);
        }

        // 3. Create Medicines
        $medicines = [];
        for ($i = 1; $i <= 10; $i++) {
            $medicines[] = Medicine::create([
                'name' => "Medicine $i",
                'brand' => "Pharma $i",
                'stock' => 100 + $i * 10,
                'unit' => 'Tablet',
                'price' => 10.50 + $i,
                'status' => 1
            ]);
        }

        // 4. Create Appointments
        foreach ($patients as $index => $patient) {
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctors[$index % count($doctors)]->id,
                'appointment_date' => Carbon::now()->addDays($index),
                'status' => 'Scheduled',
                'notes' => 'Routine checkup',
                'followup_date' => Carbon::now()->addDays($index + 7)->format('Y-m-d')
            ]);
        }

        // 5. Create Prescriptions
        foreach ($patients as $index => $patient) {
            $prescription = Prescription::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctors[$index % count($doctors)]->id,
                'date' => Carbon::now()->subDays($index)->format('Y-m-d'),
                'next_followup_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'notes' => 'Take rest'
            ]);

            // Add Prescription Items
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_id' => $medicines[0]->id,
                'dosage' => '1-0-1',
                'duration' => '5 days',
                'quantity' => 10,
                'timing' => 'Morning, Night',
                'instructions' => 'After food'
            ]);
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_id' => $medicines[1]->id,
                'dosage' => '0-0-1',
                'duration' => '3 days',
                'quantity' => 3,
                'timing' => 'Night',
                'instructions' => 'Before sleep'
            ]);
        }
    }
}
