<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->enum('subscription_plan', ['free', 'monthly', 'yearly', 'lifetime']);
            // $table->decimal('price', 8, 2);
            $table->float('price', 8);
            $table->enum('billing_cycle', ['monthly', 'yearly', 'lifetime']);
            $table->integer('touch_points')->nullable()->default(null);
            $table->boolean('has_ads');
            $table->boolean('icon');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('plans');
    }
};
