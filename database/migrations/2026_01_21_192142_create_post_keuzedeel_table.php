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
        Schema::create('keuzedeels', function (Blueprint $table) {
            $table->id();
            $table->string('titel');
            $table->binary('image');
            $table->string('code')->unique();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->text('description');
            $table->timestamps();
        });
        
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_keuzedeel');
    }
};
