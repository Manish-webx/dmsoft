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
            $table->json('youtube_links')->nullable()->after('youtube_link');
        });

        \Illuminate\Support\Facades\DB::table('prescriptions')->whereNotNull('youtube_link')->orderBy('id')->chunk(100, function ($prescriptions) {
            foreach ($prescriptions as $p) {
                \Illuminate\Support\Facades\DB::table('prescriptions')
                    ->where('id', $p->id)
                    ->update(['youtube_links' => json_encode([$p->youtube_link])]);
            }
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('youtube_link');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('youtube_link')->nullable()->after('problem_id');
            $table->dropColumn('youtube_links');
        });
    }
};
