<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_timetables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('standardLink_id')->unsigned();
            $table->foreign('standardLink_id')->references('id')->on('standards_link');
            $table->integer('teacherLink_id')->unsigned()->nullable();
            $table->foreign('teacherLink_id')->references('id')->on('class_teacher_links');
            $table->integer('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->string('subject_name');
            $table->text('day');
            $table->longText('schedule');
            $table->boolean('status')->default(1);

            $table->integer('nonce')->unsigned();
            
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
        Schema::dropIfExists('temp_timetables');
    }
}
