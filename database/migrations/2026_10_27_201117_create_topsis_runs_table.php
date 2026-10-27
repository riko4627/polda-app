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
        Schema::create('topsis_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('position_id')->constrained('positions')->cascadeOnDelete();
            $table->foreignUuid('executed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('run_date');
            $table->unsignedSmallInteger('period_year');
            $table->unsignedInteger('total_candidates')->default(0);
            $table->unsignedInteger('eligible_candidates')->default(0);
            $table->enum('status', ['pending', 'completed', 'approved', 'rejected'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topsis_runs');
    }
};
