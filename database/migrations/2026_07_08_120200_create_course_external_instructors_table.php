<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_external_instructors', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->string('full_name');
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('specialization')->nullable();

            // Unused in Phase 1 (no portal login yet); reserved as the
            // upgrade path for when a school wants this person to log in.
            $table->integer('linked_user_id')->unsigned()->nullable();
            $table->foreign('linked_user_id')->references('id')->on('users');

            $table->string('status')->default('active');

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_external_instructors');
    }
};
