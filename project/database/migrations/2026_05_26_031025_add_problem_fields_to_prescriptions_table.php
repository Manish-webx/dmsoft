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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreignId('problem_id')->nullable()->constrained('problems')->nullOnDelete()->after('notes');
            $table->string('youtube_link')->nullable()->after('problem_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['problem_id']);
            $table->dropColumn(['problem_id', 'youtube_link']);
        });
    }
};
