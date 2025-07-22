<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('touch_point_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('touch_point_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->enum('contact_method', ['call', 'text', 'meetup']);
            $table->date('completed_date');
            $table->date('original_due_date');
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->index(['user_id', 'completed_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('touch_point_histories');
    }
};
