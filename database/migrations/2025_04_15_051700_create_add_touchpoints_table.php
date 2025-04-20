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
        Schema::create('add_touchpoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('number');
            $table->enum('contact_type', ['personal', 'business']);
            $table->enum('contact_method', ['call', 'text', 'meetup']);
            $table->date('start_date');
            $table->time('start_time');
            $table->enum('cadence', ['daily', 'weekly', 'monthly', 'custom']);
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->enum('touchpoints', ['completed', 'upcoming'])->nullable();
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
        Schema::dropIfExists('add_touchpoints');
    }
};
