<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Self-referencing: a course may require completion of another
        // course first (e.g. "Spoken Hindi Level 2" requires "Spoken Hindi
        // Level 1"). Optional — most courses have none.
        Schema::create('course_prerequisites', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');

            $table->integer('prerequisite_course_id')->unsigned();
            $table->foreign('prerequisite_course_id')->references('id')->on('courses');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_prerequisites');
    }
};
