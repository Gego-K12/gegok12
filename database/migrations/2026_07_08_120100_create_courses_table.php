<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('course_categories');

            $table->string('code')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->text('prerequisites')->nullable();

            $table->integer('min_standard_id')->unsigned()->nullable();
            $table->foreign('min_standard_id')->references('id')->on('standards');
            $table->integer('max_standard_id')->unsigned()->nullable();
            $table->foreign('max_standard_id')->references('id')->on('standards');

            $table->smallInteger('duration_value')->unsigned()->nullable();
            $table->string('duration_unit')->default('weeks');
            $table->string('mode')->default('in_person');
            $table->string('banner_image')->nullable();

            $table->string('status')->default('draft');

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'slug']);
            $table->index(['school_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
