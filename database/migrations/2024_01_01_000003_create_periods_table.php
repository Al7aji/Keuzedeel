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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year'); // e.g., "2024-2025"
            $table->integer('period_number'); // 1, 2, 3, 4
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('enrollment_open')->default(false);
            $table->timestamp('enrollment_start')->nullable();
            $table->timestamp('enrollment_end')->nullable();
            $table->timestamps();

            $table->unique(['academic_year', 'period_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
