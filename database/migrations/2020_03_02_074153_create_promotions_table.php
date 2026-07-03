<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('current_academic_year_id')->unsigned();
            $table->foreign('current_academic_year_id')->references('id')->on('academic_years');
            $table->integer('current_standard_id')->unsigned();
            $table->foreign('current_standard_id')->references('id')->on('standards');
            $table->integer('current_section_id')->unsigned()->nullable();
            $table->foreign('current_section_id')->references('id')->on('sections');
            $table->integer('exam_id')->unsigned()->nullable();
            // $table->foreign('exam_id')->references('id')->on('exams');//imp
            $table->integer('next_academic_year_id')->unsigned()->nullable();
            $table->foreign('next_academic_year_id')->references('id')->on('academic_years');
            $table->integer('next_standard_id')->unsigned()->nullable();
            $table->foreign('next_standard_id')->references('id')->on('standards');
            $table->integer('next_section_id')->unsigned()->nullable();
            $table->foreign('next_section_id')->references('id')->on('sections');
            $table->string('comments')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('promotions');
    }
}
