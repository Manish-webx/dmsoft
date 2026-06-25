<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'patients',
            'appointments',
            'prescriptions',
            'revenues',
            'medicines',
            'medicine_transactions'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('location')->default('Gurgaon');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'patients',
            'appointments',
            'prescriptions',
            'revenues',
            'medicines',
            'medicine_transactions'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }
    }
};
