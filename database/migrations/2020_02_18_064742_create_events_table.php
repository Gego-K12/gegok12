<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('standard_id')->unsigned()->nullable();
            $table->foreign('standard_id')->references('id')->on('standards_link');
            $table->string('batch');
            $table->enum('select_type', ['school', 'class', 'alumni'])->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('repeats')->nullable()->default(0);
            $table->integer('freq')->nullable()->default(0);
            $table->string('freq_term')->nullable();
            $table->text('location')->nullable();
            $table->enum('category', ['culturals', 'education', 'exam', 'holidays', 'meeting'])->nullable();
            $table->longtext('organised_by')->nullable();
            $table->text('image')->nullable();
            $table->string('color');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('allDay')->default(1);
            $table->text('url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('events');
    }
}
