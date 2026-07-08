<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('usergroup_id')->unsigned();
            $table->foreign('usergroup_id')->references('id')->on('usergroups');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('alternate_no')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('blood_group', ['a+', 'a1+', 'b+', 'b1+', 'o+', 'ab+', 'a1b+', 'a-', 'a1-', 'b-', 'b1-', 'o-', 'ab-', 'a1b-'])->nullable();
            $table->enum('profession', ['admin', 'business', 'central_government_employee', 'private', 'home_maker', 'state_government_employee', 'teacher', 'librarian', 'others'])->nullable(); // change in userprofile controller - API,importmember controller,create component,edit component
            $table->enum('marital_status', ['divorced', 'married', 'single', 'widowed'])->nullable();
            $table->text('birth_place')->nullable();
            $table->text('native_place')->nullable();
            $table->text('mother_tongue')->nullable();
            $table->enum('caste', ['BC', 'BCM', 'FC', 'MBC', 'OBC', 'Others', 'SC', 'SCA', 'ST'])->nullable();
            $table->string('sub_caste')->nullable();
            $table->text('address')->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('states');
            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('pincode')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->integer('total_periods')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('EMIS_number')->nullable();
            $table->date('joining_date')->nullable();
            $table->longtext('notes')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'exit'])->default('active');
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
        Schema::dropIfExists('userprofiles');
    }
}
