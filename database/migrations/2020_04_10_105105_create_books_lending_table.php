<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksLendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books_lending', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('book_code_no');
            $table->integer('library_card_no');
            $table->date('issue_date')->nullable();
            $table->date('return_date')->nullable();
            $table->integer('issued_by')->unsigned()->nullable();
            $table->foreign('issued_by')->references('id')->on('users');
            $table->enum('status', ['pending', 'returned', 'cancel']);
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
        Schema::dropIfExists('books_lending');
    }
}
