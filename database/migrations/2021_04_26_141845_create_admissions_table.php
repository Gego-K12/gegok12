<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned()->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('standard_id')->unsigned()->nullable();
            $table->foreign('standard_id')->references('id')->on('standards');
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('height');
            $table->string('weight');
            $table->text('birth_place')->nullable();
            $table->text('nationality')->nullable();
            $table->string('avatar')->nullable();
            $table->text('religion')->nullable();
            $table->string('community')->nullable();
            $table->text('mother_tongue')->nullable();
            $table->string('identification_marks')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('blood_group')->nullable();
            $table->longText('school_last_studied')->nullable();
            $table->longText('reason_for_leaving');
            $table->longText('permanent_address');
            $table->longText('address_for_communication');
            $table->enum('siblings', ['yes', 'no'])->nullable();
            $table->string('half_yearly_mark_details');
            $table->enum('board_of_education', ['CBSE', 'Matric', 'ICSE', 'State Board', 'Anglo Indian', 'Others'])->nullable();
            $table->enum('choice_of_language', ['Tamil', 'English', 'Sanskrit', 'French'])->nullable();
            $table->longText('group_selection')->nullable();
            $table->string('board_registration_number')->nullable();

            // parent details
            $table->string('father_name');
            $table->integer('father_qualification_id')->unsigned()->nullable();
            $table->foreign('father_qualification_id')->references('id')->on('qualifications');
            $table->string('father_designation')->nullable();
            $table->string('father_occupation');
            $table->string('father_organisation')->nullable();
            $table->double('father_income')->nullable();
            $table->string('father_mobile_no');
            $table->string('father_email')->unique()->nullable();
            $table->string('father_aadhar_number')->nullable();
            $table->string('father_avatar')->nullable();

            $table->string('mother_name');
            $table->integer('mother_qualification_id')->unsigned()->nullable();
            $table->foreign('mother_qualification_id')->references('id')->on('qualifications');
            $table->string('mother_designation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_organisation')->nullable();
            $table->double('mother_income')->nullable();
            $table->string('mother_mobile_no')->nullable();
            $table->string('mother_email')->unique()->nullable();
            $table->string('mother_aadhar_number')->nullable();
            $table->string('mother_avatar')->nullable();

            $table->string('emergency_contact_1');
            $table->string('relation_with_student_1');

            $table->string('emergency_contact_2');
            $table->string('relation_with_student_2');

            $table->enum('medical_history', ['yes', 'no'])->nullable();
            $table->string('medical_details')->nullable();

            $table->enum('extra_curricular_activities', ['yes', 'no'])->nullable();
            $table->string('activities')->nullable();

            $table->enum('mode_of_transport', ['auto', 'car', 'city_bus', 'cycle', 'rickshaw', 'school_bus', 'taxi', 'walking'])->nullable();

            $table->longText('transport_details')->nullable();
            $table->string('application_no');
            $table->enum('application_status', ['Draft', 'Approved', 'Pending', 'Rejected']);
            $table->integer('section_id')->unsigned()->nullable();
            $table->foreign('section_id')->references('id')->on('sections');
            $table->string('payment_status');
            $table->integer('fee_group_id')->unsigned()->nullable();
            //  $table->foreign('fee_group_id')->references('id')->on('fee_group');//imp
            $table->longText('remarks');
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
        Schema::dropIfExists('admissions');
    }
}
