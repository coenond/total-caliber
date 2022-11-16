<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');

            $table->dateTime('start');
            $table->dateTime('end');

            $table->timestamps();
        });
        
        Schema::create('user_goal_activity_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_goal_id');
            $table->unsignedBigInteger('type_id');
        });

        Schema::table('user_goals', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('user_goal_activity_types', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('strava_sport_types');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_goal_activity_types');
        Schema::dropIfExists('user_goals');
    }
};
