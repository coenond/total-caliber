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

        StravaSportType::whereIn('type', [
            'Run',
            'TrailRun',
            'VirtualRun',
        ])->update(['group' => 'Run']);

        StravaSportType::whereIn('type', [
            'AlpineSki',
            'BackcountrySki',
            'NordicSki',
            'RollerSki',
        ])->update(['group' => 'Ski']);

        $others =  StravaSportType::whereNull('group')->get();
        foreach ($others as $type) {
            $type->group = $type->type;
            $type->save();
        }
    }

    public function down()
    {
        Schema::table('strava_sport_types', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
};
