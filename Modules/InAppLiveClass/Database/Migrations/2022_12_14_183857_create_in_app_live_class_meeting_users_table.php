<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInAppLiveClassMeetingUsersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('in_app_live_class_meeting_users')) {
            Schema::create('in_app_live_class_meeting_users', function (Blueprint $table) {
                $table->id();
                $table->integer('meeting_id')->default(1);
                $table->integer('user_id')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('in_app_live_class_meeting_users');
    }
}
