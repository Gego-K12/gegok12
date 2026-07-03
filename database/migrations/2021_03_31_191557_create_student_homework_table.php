<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentHomeworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_homework', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('homework_id')->unsigned();
            $table->foreign('homework_id')->references('id')->on('homeworks');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->longText('attachment');
            $table->date('submitted_on')->nullable();
            $table->integer('checked_by')->unsigned()->nullable();
            $table->foreign('checked_by')->references('id')->on('users');
            $table->date('checked_on')->nullable();
            $table->enum('status', ['unchecked', 'checked'])->default('unchecked');
            $table->longText('comments')->nullable();
            $table->longText('reply_comment')->nullable();
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
        Schema::dropIfExists('student_homework');
    }
}
