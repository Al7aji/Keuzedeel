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
        Schema::create('keuzedeel_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keuzedeel_id')->constrained('keuzedelen')->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->integer('instance_number')->default(1); // For repeatable keuzedelen (e.g., "Verdieping Software 1, 2, 3")
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // Instance-specific notes
            $table->timestamps();

            $table->unique(['keuzedeel_id', 'period_id', 'instance_number'], 'ki_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuzedeel_instances');
    }
};
