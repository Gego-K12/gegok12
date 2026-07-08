<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->string('transaction_no');
            $table->integer('paytype_id')->unsigned()->nullable();
            $table->foreign('paytype_id')->references('id')->on('transaction_types');
            $table->integer('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('transaction_accounts');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('users');
            $table->integer('payroll_id')->unsigned()->nullable();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
            $table->date('transaction_date');
            $table->string('amount');
            $table->enum('payment_method', ['Cash', 'Cheque', 'Bank', 'Others']);
            $table->text('transaction_detail')->nullable();
            $table->text('reference_number')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('payroll_transactions');
    }
}
