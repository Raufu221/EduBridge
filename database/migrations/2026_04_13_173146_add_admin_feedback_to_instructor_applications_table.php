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
        Schema::table('instructor_applications', function (Blueprint $table) {
            $table->text('admin_feedback')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructor_applications', function (Blueprint $table) {
            if (Schema::hasColumn('instructor_applications', 'admin_feedback')) {
                $table->dropColumn('admin_feedback');
            }
        });
    }
};
