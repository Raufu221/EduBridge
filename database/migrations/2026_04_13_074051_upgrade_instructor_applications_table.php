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
            $table->foreignId('user_id')->nullable()->change();
            $table->string('full_name')->after('user_id');
            $table->string('email')->after('full_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('linkedin')->nullable()->after('phone');
            $table->string('expertise')->after('linkedin');
            $table->integer('experience_years')->after('expertise');
            $table->string('portfolio')->nullable()->after('experience_years');
            $table->string('proposal_topic')->after('portfolio');
            $table->text('teaching_approach')->after('proposal_topic');
        });
    }

    public function down(): void
    {
        Schema::table('instructor_applications', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn([
                'full_name', 'email', 'phone', 'linkedin', 
                'expertise', 'experience_years', 'portfolio', 
                'proposal_topic', 'teaching_approach'
            ]);
        });
    }
};
