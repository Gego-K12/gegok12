<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Certificatetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_certificate', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->bigInteger('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('student_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on('users');
            $table->string('program_name');
            $table->string('event_name');
            $table->string('certificate_for');
            $table->date('date');
            $table->integer('standardLink_id')->unsigned();
            $table->foreign('standardLink_id')->references('id')->on('standards_link');
            $table->string('standard')->nullable();
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
        //
    }
}
