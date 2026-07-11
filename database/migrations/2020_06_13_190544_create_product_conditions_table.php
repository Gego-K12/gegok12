<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_conditions', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedinteger('product_unique_code');
           $table->foreign('product_unique_code')->references('id')->on('product_codes');
           $table->string('condition');
           $table->date('date');
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
        Schema::dropIfExists('product_conditions');
    }
}
