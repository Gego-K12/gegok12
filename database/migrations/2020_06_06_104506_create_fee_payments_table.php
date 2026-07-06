<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fee_id');
            $table->foreign('fee_id')->references('id')->on('fees');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('paid_amount')->nullable();
            $table->boolean('concession_applied')->nullable();
            $table->date('paid_on')->nullable();
            $table->string('comments')->nullable();
            $table->boolean('notify_parent')->nullable();
            $table->boolean('status');
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('fee_payments');
    }
}