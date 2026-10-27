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
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('personnel_id')->constrained('personnel')->cascadeOnDelete();
            $table->foreignUuid('criterion_id')->constrained('criteria')->cascadeOnDelete();
            $table->decimal('score', 8, 2);
            $table->unsignedSmallInteger('period_year');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['personnel_id', 'criterion_id', 'period_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};
