<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('net_paid', 10, 2);
            $table->decimal('commission_amount', 10, 2); // 30%
            $table->decimal('instructor_amount', 10, 2); // 70%
            
            $table->string('payment_method'); // stripe, manual_bkash, manual_nagad
            $table->string('gateway_ref')->nullable(); // Stripe session ID
            $table->string('sender_phone')->nullable();
            $table->string('manual_trx_id')->nullable();
            
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'rejected'])->default('pending');
            $table->timestamp('clearance_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
