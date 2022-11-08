<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('strava_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('strava_id');
            $table->unsignedBigInteger('user_id');
            $table->string('username');
            $table->string('firstname');
            $table->string('lastname'); 
            $table->string('pic_large');
            $table->string('pic_medium');
            $table->boolean('metric')->default(true);

            $table->timestamps();
        });

        Schema::table('strava_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('strava_profiles');
    }
};
