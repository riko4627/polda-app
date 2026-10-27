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
        Schema::create('career_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('personnel_id')->constrained('personnel')->cascadeOnDelete();
            $table->string('position_title');
            $table->string('unit_name')->nullable();
            $table->string('function_track', 50)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('sk_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_histories');
    }
};
