<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizTestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_test_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('test_id');
            $table->foreign('test_id')->references('id')->on('quiz_tests')->onDelete('cascade');
            $table->unsignedInteger('question_id');
            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
            $table->text('answer');
            $table->boolean('flag')->default('0');
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
        Schema::dropIfExists('quiz_test_answers');
    }
}
