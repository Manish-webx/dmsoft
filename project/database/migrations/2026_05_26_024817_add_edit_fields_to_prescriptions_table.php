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
            $table->boolean('is_edited')->default(false)->after('notes');
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete()->after('is_edited');
            $table->timestamp('edited_at')->nullable()->after('edited_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['edited_by']);
            $table->dropColumn(['is_edited', 'edited_by', 'edited_at']);
        });
    }
};
