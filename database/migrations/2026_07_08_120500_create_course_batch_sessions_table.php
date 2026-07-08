<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_batch_sessions', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('course_batch_id')->unsigned();
            $table->foreign('course_batch_id')->references('id')->on('course_batches');

            $table->smallInteger('session_no')->unsigned();
            $table->date('session_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('topic')->nullable();
            $table->string('venue_override')->nullable();

            // Substitute-teacher override for one specific session.
            $table->integer('instructor_id')->unsigned()->nullable();
            $table->foreign('instructor_id')->references('id')->on('course_instructors');

            $table->string('status')->default('scheduled');
            $table->string('cancelled_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_batch_id', 'session_no']);
            $table->index('session_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_batch_sessions');
    }
};
