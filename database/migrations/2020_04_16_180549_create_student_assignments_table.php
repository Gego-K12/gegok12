<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->longText('assignment_file');
            $table->integer('obtained_marks')->nullable();
            $table->date('submitted_on');
            $table->longText('comments')->nullable();
            $table->integer('marks_given_by')->unsigned()->nullable();
            $table->foreign('marks_given_by')->references('id')->on('users');
            $table->date('marks_given_on')->nullable();
            $table->enum('status', ['pending', 'submitted', 'cancel', 'completed']);
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
        Schema::dropIfExists('student_assignments');
    }
}
