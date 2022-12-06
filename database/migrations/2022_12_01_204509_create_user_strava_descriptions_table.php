<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_strava_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->boolean('enabled')->default(true);
            $table->boolean('totals')->default(true);
            $table->boolean('week_stats')->default(true);
            $table->boolean('month_stats')->default(true);

            $table->timestamps();
        });

        Schema::table('user_strava_descriptions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_strava_descriptions');
    }
};
