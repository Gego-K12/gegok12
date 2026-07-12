<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('standard_id')->unsigned();
            $table->foreign('standard_id')->references('id')->on('standards_link');
            $table->integer('exam_rule_id')->unsigned();
            $table->foreign('exam_rule_id')->references('id')->on('exam_rules');
            $table->string('name');
            $table->enum('exam_type',['term','classtest','final']);
            //$table->enum('grade_type',['scholastic','nonscholastic']);
            $table->integer('sc_grade')->unsigned()->nullable();
            $table->foreign('sc_grade')->references('id')->on('sc_grade');
            $table->integer('non_sc_grade')->unsigned()->nullable();
            $table->foreign('non_sc_grade')->references('id')->on('non_sc_grade');
            //$table->integer('total_marks');
            $table->boolean('status')->default('1');
            $table->integer('dev_flag')->default('0');//for development
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
        Schema::dropIfExists('exams');
    }
}
