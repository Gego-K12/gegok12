<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
          $table->increments('id');
             $table->bigInteger('school_id')->unsigned();
             $table->foreign('school_id')->references('id')->on('schools');
             $table->string('name');
             $table->text('specification')->nullable();
             $table->unsignedinteger('category_id');
             $table->foreign('category_id')->references('category_id')->on('category_vendors');
             $table->unsignedinteger('vendor_id');
             $table->foreign('vendor_id')->references('vendor_id')->on('category_vendors');
             $table->string('unique_code_prefix');
             $table->bigInteger('quantity');
             $table->date('purchased_date');
             $table->enum('product_type',['sellable','non_sellable']);
             $table->text('warranty_period')->nullable();
             $table->date('warranty_enddate')->nullable();
             $table->string('cost_per_quantity');
             $table->string('total_price');
             $table->string('depreciation_rate')->nullable();
             $table->boolean('in_use')->nullable();
             $table->text('bill_scan')->nullable();
             $table->string('remarks');
             $table->boolean('ownership_tracking')->nullable();
             $table->boolean('location_tracking')->nullable();
             $table->boolean('maintainence_tracking')->nullable();
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
        Schema::dropIfExists('products');
    }
}
