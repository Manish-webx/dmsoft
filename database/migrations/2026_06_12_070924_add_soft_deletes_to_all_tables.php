<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('appointments', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('medicines', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('prescriptions', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('problems', function (Blueprint $table) { $table->softDeletes(); });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('appointments', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('medicines', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('prescriptions', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('problems', function (Blueprint $table) { $table->dropSoftDeletes(); });
    }
};
