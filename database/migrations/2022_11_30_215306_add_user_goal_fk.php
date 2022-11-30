<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_goal_activity_types', function (Blueprint $table) {
            $table->foreign('user_goal_id')->references('id')->on('user_goals');
        });
    }

    public function down()
    {
        Schema::table('user_goal_activity_types', function (Blueprint $table) {
            $table->dropForeign('user_goal_activity_types_user_goal_id_foreign');
        });
    }
};
