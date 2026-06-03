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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->string('dosage')->nullable(); // e.g., '500mg'
            $table->string('timing')->nullable(); // e.g., '1-0-1' or 'Morning, Night'
            $table->string('duration')->nullable(); // e.g., '5 days'
            $table->integer('quantity'); // Total tablets/ml prescribed
            $table->string('instructions')->nullable(); // Before food, After food
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
