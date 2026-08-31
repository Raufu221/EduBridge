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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            // Links the lesson to a specific module
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->text('content')->nullable(); // For text/article lessons
            
            // The types match the exact buttons on your UI's right sidebar!
            $table->enum('type', ['video', 'article', 'quiz', 'assignment', 'resource'])->default('video');
            
            $table->string('video_url')->nullable();
            $table->integer('duration')->nullable(); // Duration in minutes
            $table->integer('order')->default(0);    // For drag-and-drop sorting
            $table->boolean('is_preview')->default(false); // Allows free preview for marketing
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
