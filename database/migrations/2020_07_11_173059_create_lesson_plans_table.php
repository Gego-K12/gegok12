<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_link_id')->unsigned();
            $table->foreign('teacher_link_id')->references('id')->on('class_teacher_links');
            $table->string('unit_no');
            $table->string('unit_name');
            $table->string('title');
            $table->time('duration');
            $table->longtext('description');
            $table->longtext('objective')->nullable();
            $table->longtext('materials_required')->nullable();
            $table->longtext('introduction')->nullable();
            $table->longtext('procedure')->nullable();
            $table->longtext('conclusion')->nullable();
            $table->longtext('assessment')->nullable();
            $table->longtext('modification')->nullable();
            $table->longtext('notes')->nullable();
            $table->enum('status',['approved','archived','cancel','draft','pending','rejected']);
            $table->boolean('is_published')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('lesson_plans');
    }
}
