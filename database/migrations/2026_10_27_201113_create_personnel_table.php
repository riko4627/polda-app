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
        Schema::create('personnel', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nrp', 20)->unique();
            $table->string('name');
            $table->foreignUuid('rank_id')->constrained('ranks')->restrictOnDelete();
            $table->string('education_level', 20)->default('SMA'); // SMA, D3, S1, S2, S3
            $table->string('education_major')->nullable();
            $table->boolean('has_sespimmen')->default(false);
            $table->string('current_position')->nullable();
            $table->string('unit_name')->nullable();
            $table->string('function_track', 50)->default('Reskrim');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status', 20)->default('active'); // active, retired, suspended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
