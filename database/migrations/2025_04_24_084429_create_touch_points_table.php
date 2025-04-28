<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('touch_points', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->boolean('is_completed')->default(false);

            $table->string('avatar')->nullable();
            $table->string('name')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->enum('contact_type', ['personal', 'business'])->nullable(false);
            $table->enum('contact_method', ['call', 'text', 'meetup'])->nullable(false);

            $table->date('touch_point_start_date')->nullable(false);
            $table->time('touch_point_start_time')->nullable(false);

            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'custom'])->nullable(false);
            $table->unsignedInteger('custom_days')->nullable(); // Only used if frequency is 'custom'

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('touch_points');
    }
};
