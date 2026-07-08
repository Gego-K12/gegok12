<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_invitations', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('course_batch_id')->unsigned();
            $table->foreign('course_batch_id')->references('id')->on('course_batches');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('invited_by')->unsigned();
            $table->foreign('invited_by')->references('id')->on('users');

            $table->string('channel')->default('admin');
            $table->string('status')->default('pending');
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('responded_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_batch_id', 'user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_invitations');
    }
};
