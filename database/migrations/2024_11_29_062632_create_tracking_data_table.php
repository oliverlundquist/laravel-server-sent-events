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
        Schema::create('tracking_data', function (Blueprint $table) {
            $table->ulid('request_id')->unique();
            $table->string('session_id')->index();
            $table->string('tenant')->index();
            $table->string('visited_page');
            $table->unsignedInteger('request_start_time');
            $table->unsignedInteger('request_last_activity_at');
            $table->unsignedInteger('session_start_time');
            $table->unsignedInteger('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_data');
    }
};
