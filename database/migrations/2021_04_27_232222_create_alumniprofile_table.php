<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumniprofileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumniprofile', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('email');
            $table->string('mobile_no');
            $table->string('photo');
            $table->string('passing_session');

            //education details
            $table->string('institution_name')->nullable();
            $table->string('degree')->nullable();
            $table->string('specialization')->nullable();
            $table->string('college_start_year')->nullable();
            $table->string('college_end_year')->nullable();
            $table->string('grade')->nullable();
            $table->string('extra_activities')->nullable();

            //job details           
            $table->string('company_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('location')->nullable();
            $table->string('job_start_year')->nullable();
            $table->string('job_start_month')->nullable();
            $table->string('job_end_year')->nullable();
            $table->string('job_end_month')->nullable();

            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('telegram')->nullable();
            $table->string('facebook')->nullable();

            $table->longText('about_me')->nullable();

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
        Schema::dropIfExists('alumniprofile');
    }
}