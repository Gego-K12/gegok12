<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->string('payrollno');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('users');
            $table->integer('salary_id')->unsigned();
            $table->foreign('salary_id')->references('id')->on('salaries');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('percentage')->default(100);
            $table->integer('leave');
            $table->integer('late')->nullable();
            $table->integer('leave_deduction');
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('payrolls');
    }
}
