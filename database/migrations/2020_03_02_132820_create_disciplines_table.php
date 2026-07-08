<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disciplines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('standardLink_id')->unsigned()->nullable();
            $table->foreign('standardLink_id')->references('id')->on('standards_link');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->dateTime('incident_date');
            $table->integer('reported_by')->unsigned();
            $table->foreign('reported_by')->references('id')->on('users');
            $table->longText('incident_detail');
            $table->string('response')->nullable();
            $table->boolean('action_taken')->default(0);
            $table->enum('type', ['discipline', 'performance', 'others'])->nullable();
            $table->enum('media_type', ['video', 'audio', 'image'])->nullable();
            $table->string('attachments')->nullable();
            $table->boolean('notify_parents')->default(0);
            $table->boolean('is_seen')->default(0);
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
        Schema::dropIfExists('disciplines');
    }
}
