<?php

namespace Database\Seeders;

use App\Enums\StravaSportTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StravaSportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sportTypes = array_map(fn ($type) => ['type' => $type->name], StravaSportTypeEnum::cases());
        DB::table('strava_sport_types')->insertOrIgnore($sportTypes);
    }
}
