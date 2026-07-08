<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendMailTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_mail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('student_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('from_address')->nullable();
            $table->text('from')->nullable();
            $table->text('to')->nullable();
            $table->text('subject')->nullable();
            $table->longtext('message')->nullable();
            $table->longtext('attachments')->nullable();
            $table->enum('status', ['queue', 'delivered', 'failed'])->nullable();
            $table->enum('type', ['mail', 'inbox', 'sent'])->nullable();
            $table->text('message_id')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->boolean('is_executed')->default(0);
            $table->timestamp('fired_at')->nullable();
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('send_mail');
    }
}
