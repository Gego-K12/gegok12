<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('category_vendors', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedinteger('category_id');
           $table->foreign('category_id')->references('id')->on('categories');
           $table->unsignedinteger('vendor_id');
           $table->foreign('vendor_id')->references('id')->on('vendors');
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
        Schema::dropIfExists('category_vendors');
    }
}
