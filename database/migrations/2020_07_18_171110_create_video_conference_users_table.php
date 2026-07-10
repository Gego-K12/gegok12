<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoConferenceUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_conference_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conference_id')->unsigned();
            $table->foreign('conference_id')->references('id')->on('video_conferences');
            $table->integer('participant_id')->unsigned();
            $table->foreign('participant_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_conference_users');
    }
}