<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('standardLink_id')->unsigned()->nullable();
            $table->foreign('standardLink_id')->references('id')->on('standards_link');
            $table->integer('fee_group_id')->unsigned()->nullable();
            $table->foreign('fee_group_id')->references('id')->on('fee_group');
            $table->string('scope')->nullable();
            $table->string('name');
            $table->string('term')->nullable();
            $table->string('amount')->nullable();
            $table->string('fee_type');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default('1');
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
        Schema::dropIfExists('fees');
    }
}