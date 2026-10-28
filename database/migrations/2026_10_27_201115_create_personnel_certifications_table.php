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
        Schema::create('personnel_certifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('personnel_id')->constrained('personnel')->cascadeOnDelete();
            $table->string('certification_name');
            $table->string('institution')->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_certifications');
    }
};
