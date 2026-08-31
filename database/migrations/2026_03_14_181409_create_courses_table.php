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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // Course Details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('what_you_will_learn')->nullable();
            $table->text('requirements')->nullable();
            $table->text('target_audience')->nullable();
            
            // --- ADDED LEVEL HERE ---
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            
            $table->decimal('price', 8, 2)->nullable();
            $table->string('cover_image')->nullable();
            
            // Admin Controls
            $table->boolean('is_published')->default(false);
            
            // --- ADDED SUBMISSION TRACKER HERE ---
            $table->boolean('is_submitted')->default(false); 
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
