<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->longtext('question');
            $table->unsignedInteger('topic_id')->nullable();
            $table->foreign('topic_id')->references('id')->on('quiz_topics')->onDelete('cascade');
            $table->unsignedInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('question_types')->onDelete('cascade');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('chapter_id')->unsigned()->nullable();
            $table->foreign('chapter_id')->references('id')->on('chapters');
            $table->integer('head_id')->unsigned()->nullable();
            $table->foreign('head_id')->references('id')->on('question_heads');
            $table->enum('type',['all','repeated','important'])->default('all');
            $table->enum('status',['active','inactive'])->default('active');
            $table->integer('page_no')->nullable();
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
        Schema::dropIfExists('quiz_questions');
    }
}
