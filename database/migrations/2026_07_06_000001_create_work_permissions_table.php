<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_permissions', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->date('date');
            $table->time('from_time');
            $table->time('to_time');
            $table->integer('duration_minutes')->unsigned();

            $table->string('type');
            $table->boolean('is_emergency')->default(0);
            $table->string('reason');
            $table->string('contact_number')->nullable();
            $table->string('attachment')->nullable();

            $table->string('status')->default('pending');
            $table->integer('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->date('approved_on')->nullable();
            $table->text('comments')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_permissions');
    }
};
