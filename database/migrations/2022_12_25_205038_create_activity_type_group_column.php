<?php

use App\Models\StravaSportType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('strava_sport_types', function (Blueprint $table) {
            $table->string('group')->nullable();
        });

        StravaSportType::whereIn('type', [
            'EBikeRide',
            'EMountainBikeRide',
            'GravelRide',
            'MountainBikeRide',
            'Ride',
            'VirtualRide'
        ])->update(['group' => 'Ride']);
    }

    public function down()
    {
        Schema::table('strava_sport_types', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
};
