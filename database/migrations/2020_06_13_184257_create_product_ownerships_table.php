<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOwnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ownerships', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedinteger('product_unique_code');
           $table->foreign('product_unique_code')->references('id')->on('product_codes');
           $table->unsignedinteger('owner_id');
           $table->foreign('owner_id')->references('id')->on('users');    
           $table->date('start_date');
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
        Schema::dropIfExists('product_ownerships');
    }
}
