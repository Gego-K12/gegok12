<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag_name')->nullable();
            // Spatie required columns

            $table->json('name')->nullable();
            $table->json('slug')->nullable();
            $table->string('type')->nullable();
            $table->integer('order_column')->nullable();

            $table->timestamps();

            // $table->unique(['slug', 'type'], 'tags_slug_type_unique');
            $table->unique(['tag_name', 'type'], 'tags_tag_name_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}