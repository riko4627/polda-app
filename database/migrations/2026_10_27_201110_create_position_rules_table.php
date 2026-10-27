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
        Schema::create('position_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('position_id')->constrained('positions')->cascadeOnDelete();
            $table->foreignUuid('min_rank_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->foreignUuid('required_rank_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->enum('required_rank_category', ['tamtama', 'bintara', 'pama', 'pamen', 'pati'])->nullable();
            $table->string('min_education_level')->nullable();
            $table->boolean('requires_sespimmen')->default(false);
            $table->string('required_certification_keyword')->nullable();
            $table->string('required_past_position_title')->nullable();
            $table->string('required_function_track')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_rules');
    }
};
