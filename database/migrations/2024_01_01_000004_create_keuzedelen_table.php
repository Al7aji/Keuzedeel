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
        Schema::create('keuzedelen', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable(); // Rich text content for CMS
            $table->boolean('is_repeatable')->default(false); // Can be taken multiple times
            $table->boolean('is_active')->default(true);
            $table->integer('max_students')->default(30);
            $table->integer('min_students')->default(15);
            $table->integer('credits')->default(0); // Study credits (EC/studiepunten)
            $table->string('image')->nullable(); // Featured image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuzedelen');
    }
};
