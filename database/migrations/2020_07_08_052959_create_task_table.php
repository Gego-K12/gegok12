<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('title');
            $table->enum('type',[
                'self',
                'student',
                'class',
                'group',
                'teacher',
                'non_teaching'
            ]);
            $table->dateTime('task_date');
            $table->enum('reminder',['one_hour_before_the_task','one_day_before_the_task','others','two_days_before_the_task']);
            $table->dateTime('reminder_date')->nullable();
            $table->string('to_do_list');
            $table->boolean('task_status')->default(false);
            $table->integer('task_flag');//0->over due, 1->today, 2->upcoming
            $table->boolean('snooze')->default(false); 

            $table->enum('priority', [
                'low',
                'normal',
                'high'
            ])->default('normal');

            $table->enum('task_type', [
                'individual',
                'group_task',
                'open'
            ])->default('individual');

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
        Schema::dropIfExists('task');
    }
}
