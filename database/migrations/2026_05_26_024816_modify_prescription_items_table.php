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
            $table->dropColumn('dosage');
            $table->foreignId('alternate_medicine_id')->nullable()->constrained('medicines')->nullOnDelete()->after('medicine_id');
            $table->text('medicine_note')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->string('dosage')->nullable()->after('medicine_id');
            $table->dropForeign(['alternate_medicine_id']);
            $table->dropColumn('alternate_medicine_id');
            $table->dropColumn('medicine_note');
        });
    }
};
