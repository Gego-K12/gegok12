<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cycle')->comment('Do not change');
            $table->text('name');
            $table->text('display_name');
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(1);
            $table->double('amount', 10, 2)->default(0);
            $table->integer('no_of_members')->nullable();
            $table->integer('no_of_events')->nullable();
            $table->integer('no_of_folders')->nullable()->comment('Gallery');
            $table->integer('no_of_files')->nullable();
            $table->integer('no_of_videos')->nullable();
            $table->integer('no_of_audios')->nullable();
            $table->integer('no_of_bulletins')->nullable();
            $table->integer('no_of_groups')->nullable();
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
        Schema::dropIfExists('plans');
    }
}
