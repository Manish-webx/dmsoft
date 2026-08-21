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
            $table->dropForeign(['alternate_medicine_id']);
        });
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->string('alternate_medicine_id')->nullable()->change();
        });

        Schema::table('prescription_template_items', function (Blueprint $table) {
            $table->dropForeign(['alternate_medicine_id']);
        });
        Schema::table('prescription_template_items', function (Blueprint $table) {
            $table->string('alternate_medicine_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->unsignedBigInteger('alternate_medicine_id')->nullable()->change();
            $table->foreign('alternate_medicine_id')->references('id')->on('medicines')->nullOnDelete();
        });

        Schema::table('prescription_template_items', function (Blueprint $table) {
            $table->unsignedBigInteger('alternate_medicine_id')->nullable()->change();
            $table->foreign('alternate_medicine_id')->references('id')->on('medicines')->onDelete('set null');
        });
    }
};
