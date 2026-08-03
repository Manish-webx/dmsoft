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
        Schema::create('prescription_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('problem_id')->nullable()->constrained('problems')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->text('hopi')->nullable();
            $table->json('youtube_links')->nullable();
            $table->timestamps();
        });

        Schema::create('prescription_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_template_id')->constrained('prescription_templates')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->foreignId('alternate_medicine_id')->nullable()->constrained('medicines')->onDelete('set null');
            $table->string('timing')->nullable();
            $table->string('duration')->nullable();
            $table->integer('quantity');
            $table->string('instructions')->nullable();
            $table->text('medicine_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_template_items');
        Schema::dropIfExists('prescription_templates');
    }
};
