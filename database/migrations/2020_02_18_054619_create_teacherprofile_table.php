<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherprofileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacherprofile', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('qualification_id')->unsigned()->nullable();
            $table->foreign('qualification_id')->references('id')->on('qualifications');
            $table->integer('ug_degree')->unsigned()->nullable();
            $table->foreign('ug_degree')->references('id')->on('qualifications');
            $table->integer('pg_degree')->unsigned()->nullable();
            $table->foreign('pg_degree')->references('id')->on('qualifications');
            $table->string('sub_qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->enum('designation', ['assistant_teacher', 'co_ordinator', 'head_of_the_department', 'librarian', 'others', 'principal', 'teacher', 'senior_teacher', 'vice_principal', 'accountant', 'receptionist', 'lab_assistant', 'clerk', 'stock_keeper', 'peon', 'driver', 'helpers', 'security', 'physical_education_teacher', 'transport_coordinator'])->nullable();
            $table->string('sub_designation')->nullable();
            $table->enum('job_type', ['full_time', 'part_time'])->nullable();
            $table->longText('interested_in')->nullable();
            $table->string('employee_id')->nullable();
            $table->integer('reporting_to')->unsigned()->nullable();
            $table->foreign('reporting_to')->references('id')->on('users');
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('teacherprofile');
    }
}
