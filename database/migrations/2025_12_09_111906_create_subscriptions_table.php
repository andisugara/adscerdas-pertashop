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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('plan_name'); // 'trial', 'monthly', 'yearly'
            $table->decimal('price', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending, active, expired, cancelled
            $table->string('payment_method')->nullable(); // 'manual', 'duitku'
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed
            $table->text('payment_proof')->nullable(); // URL bukti transfer (for manual)
            $table->string('merchant_order_id')->nullable();
            $table->string('duitku_reference')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
