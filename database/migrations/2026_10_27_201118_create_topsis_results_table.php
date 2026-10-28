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
        Schema::create('topsis_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('topsis_run_id')->constrained('topsis_runs')->cascadeOnDelete();
            $table->foreignUuid('personnel_id')->constrained('personnel')->cascadeOnDelete();
            $table->boolean('is_eligible')->default(true);
            $table->json('ineligibility_reasons')->nullable();
            $table->decimal('d_plus', 12, 6)->nullable();
            $table->decimal('d_minus', 12, 6)->nullable();
            $table->decimal('preference_score', 12, 6)->nullable();
            $table->unsignedInteger('rank_position')->nullable();
            $table->json('matrix_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topsis_results');
    }
};
