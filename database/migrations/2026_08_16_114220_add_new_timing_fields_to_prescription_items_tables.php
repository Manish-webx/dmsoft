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
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->string('time')->nullable()->after('alternate_medicine_id');
            $table->string('when')->nullable()->after('time');
            $table->string('frequency')->nullable()->after('when');
        });

        Schema::table('prescription_template_items', function (Blueprint $table) {
            $table->string('time')->nullable()->after('alternate_medicine_id');
            $table->string('when')->nullable()->after('time');
            $table->string('frequency')->nullable()->after('when');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropColumn(['time', 'when', 'frequency']);
        });

        Schema::table('prescription_template_items', function (Blueprint $table) {
            $table->dropColumn(['time', 'when', 'frequency']);
        });
    }
};
