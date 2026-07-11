<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_tracking', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('route_id')->unsigned()->nullable();
            $table->foreign('route_id')->references('id')->on('vehicle_routes');
            $table->integer('driver_id')->unsigned()->nullable();
            $table->foreign('driver_id')->references('id')->on('users');
            $table->enum('type',['in','out','others'])->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('checkout_at')->nullable();
            $table->string('start_km')->nullable();
            $table->string('end_km')->nullable();
            $table->string('start_at_comments')->nullable();
            $table->string('end_at_comments')->nullable();
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
        Schema::dropIfExists('transport_tracking');
    }
}
