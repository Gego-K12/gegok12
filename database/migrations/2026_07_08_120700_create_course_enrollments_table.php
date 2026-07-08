<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('course_batch_id')->unsigned();
            $table->foreign('course_batch_id')->references('id')->on('course_batches');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('course_invitation_id')->unsigned()->nullable();
            $table->foreign('course_invitation_id')->references('id')->on('course_invitations');

            $table->string('source')->default('invited');

            // String, not a DB enum: invited/enrolled/active/completed/dropped
            // in Phase 1; on_break/re_entered/certified values are reserved
            // for Phase 3 and need no migration to add.
            $table->string('status')->default('invited');

            $table->date('enrolled_on')->nullable();
            $table->date('activated_on')->nullable();
            $table->date('completed_on')->nullable();
            $table->date('dropped_on')->nullable();
            $table->date('certified_on')->nullable();
            $table->string('drop_reason')->nullable();
            $table->string('roll_number')->nullable();

            // Reserved for Phase 3 approval-gated flows (e.g. self-enroll
            // moderation, break/re-entry); unused in Phase 1.
            $table->integer('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->date('approved_on')->nullable();

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_batch_id', 'user_id']);
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollments');
    }
};
