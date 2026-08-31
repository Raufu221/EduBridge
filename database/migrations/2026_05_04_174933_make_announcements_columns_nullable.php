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
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->change();
            $table->foreignId('instructor_id')->nullable()->change();
            $table->string('target_audience')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable(false)->change();
            $table->foreignId('instructor_id')->nullable(false)->change();
            $table->dropColumn('target_audience');
        });
    }
};
