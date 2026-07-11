<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->unsignedinteger('product_unique_code');
            $table->foreign('product_unique_code')->references('id')->on('product_codes');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('location_products');
    }
}
