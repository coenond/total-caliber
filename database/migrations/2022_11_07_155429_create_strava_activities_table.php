<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strava_activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('strava_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_id');
            $table->string('name');
            $table->float('distance');
            $table->bigInteger('moving_time'); 
            $table->float('total_elevation_gain');
            $table->dateTime('start_date');
            $table->integer('utc_offset');
            $table->float('calories')->default(0);
            $table->boolean('trainer')->default(false);
            $table->boolean('commute')->default(false);
            $table->boolean('manual')->default(false);
            $table->boolean('private')->default(false);

            $table->timestamps();
        });

        Schema::table('strava_activities', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('strava_sport_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('strava_activities');
    }
};
