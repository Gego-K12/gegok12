<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_attendances', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            // Kept redundantly alongside course_batch_session_id so the
            // plugin's scoping trait works uniformly on every model with
            // no special-casing.
            $table->integer('course_batch_id')->unsigned();
            $table->foreign('course_batch_id')->references('id')->on('course_batches');

            $table->integer('course_batch_session_id')->unsigned();
            $table->foreign('course_batch_session_id')->references('id')->on('course_batch_sessions');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            // 0=absent, 1=present, 2=late, 3=excused — matches
            // App\Models\Attendance's integer convention.
            $table->tinyInteger('status')->unsigned();

            // Reuses the existing global (non-tenant-scoped) absent_reasons
            // lookup table rather than duplicating it.
            $table->integer('reason_id')->unsigned()->nullable();
            $table->foreign('reason_id')->references('id')->on('absent_reasons');

            $table->string('remarks')->nullable();

            $table->integer('recorded_by')->unsigned();
            $table->foreign('recorded_by')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_batch_session_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_attendances');
    }
};
