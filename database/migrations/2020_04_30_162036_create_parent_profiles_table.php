<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('qualification_id')->unsigned()->nullable();
            $table->foreign('qualification_id')->references('id')->on('qualifications');
            $table->enum('profession', ['admin', 'business', 'central_government_employee', 'private', 'home_maker', 'state_government_employee', 'teacher', 'librarian', 'others'])->nullable(); // change in userprofile controller - API,importmember controller,create component,edit component
            $table->string('sub_occupation')->nullable();
            $table->string('designation')->nullable();
            $table->string('organization_name')->nullable();
            $table->text('official_address')->nullable();
            $table->string('relation')->nullable();
            $table->double('annual_income')->nullable();
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
        Schema::dropIfExists('parent_profiles');
    }
}
