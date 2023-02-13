<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_strava_descriptions', function (Blueprint $table) {
            $table->boolean('simple')->after('enabled')->default(false);
        });
    }

    public function down()
    {
        Schema::table('user_strava_descriptions', function (Blueprint $table) {
            $table->dropColumn('simple');
        });
    }
};
