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
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('has_won')->default(false); // Flag to indicate if the game was won
            $table->boolean('has_lost')->default(false); // Flag to indicate if the game was lost
            $table->string('segment_type')->nullable(); // The type of segment (nullable, can be empty)
            $table->json('tiles')->nullable(); // The tiles stored in JSON format (nullable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['has_won', 'has_lost', 'segment_type', 'tiles']);
        });
    }
};
