<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_academics', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('standardLink_id')->unsigned()->nullable();
            $table->foreign('standardLink_id')->references('id')->on('standards_link');
            $table->string('roll_number')->nullable();
            $table->string('id_card_number')->nullable();
            $table->string('board_registration_number')->nullable();
            $table->enum('mode_of_transport', ['auto', 'car', 'city_bus', 'cycle', 'rickshaw', 'school_bus', 'taxi', 'walking'])->nullable();
            $table->longText('transport_details')->nullable();
            $table->enum('siblings', ['yes', 'no'])->nullable();
            $table->string('siblings_count')->nullable();
            $table->longText('sibling_details')->nullable();
            $table->float('height', 8, 2)->nullable();
            $table->float('weight', 8, 2)->nullable();
            $table->longText('medication_problems')->nullable();
            $table->longText('medication_needs')->nullable();
            $table->longText('medication_allergies')->nullable();
            $table->longText('food_allergies')->nullable();
            $table->longText('other_allergies')->nullable();
            $table->longText('other_medical_information')->nullable();
            $table->enum('academic_status', ['pass', 'fail'])->nullable();
            $table->enum('bus_pass', ['yes', 'no'])->nullable();
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
        Schema::dropIfExists('student_academics');
    }
}
