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
        Schema::create('subscription_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_subscription_id')->nullable();
            $table->string('payment_customer_id')->nullable();
            $table->string('subscription_plan_price_id');
            $table->decimal('plan_amount',10,2);
            $table->string('plan_amount_currency');
            $table->string('plan_interval');
            $table->string('plan_interval_count');
            $table->timestamp('created');
            $table->timestamp('plan_period_start')->nullable();
            $table->timestamp('plan_period_end')->nullable();
            $table->timestamp('trial_end')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_details');
    }
};
