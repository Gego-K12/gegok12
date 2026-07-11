<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->string('name');
            $table->string('code');
            $table->string('vehicle_number');
            $table->enum('vehicle_type',['Bus','Van','Jeep','Others'])->nullable();
            $table->enum('fuel_type',['Petrol','Disel','CNG','Others'])->nullable();
            $table->enum('ownership_status',['Owned','Contract'])->nullable();
            $table->string('chassis_number');
            $table->string('engine_number');
            $table->string('registration_number');
            $table->date('registration_date');
            $table->integer('seat')->nullable();
            $table->text('specification')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('availability')->default(1);
            $table->boolean('status')->default(1);
            //$table->date('start_date')->nullable();
            //$table->date('end_date')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
