<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->string('name');
            $table->string('slug');
            $table->string('icon')->nullable();
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_categories');
    }
};
