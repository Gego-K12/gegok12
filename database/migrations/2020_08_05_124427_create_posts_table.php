<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('entity_id');
            $table->string('entity_name');
            $table->longText('description');
            $table->longText('attachment_file')->nullable();
            $table->enum('visibility', ['all_class', 'select_class', 'select_page'])->nullable();
            $table->integer('visible_for')->unsigned()->nullable();
            $table->foreign('visible_for')->references('id')->on('standards_link');
            $table->timestamp('post_created_at')->nullable();
            $table->boolean('is_posted')->default(0);
            $table->timestamp('posted_at')->nullable();
            $table->enum('status', ['drafted', 'pending', 'posted', 'cancelled'])->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('posts');
    }
}
