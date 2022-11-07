<?php

use Database\Seeders\StravaSportTypeSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('strava_sport_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
        });

        Artisan::call('db:seed', [ '--class' => StravaSportTypeSeeder::class]);
    }

    public function down()
    {
        Schema::dropIfExists('strava_sport_types');
    }
};
