<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academy_group_settings', function (Blueprint $t) {
            $t->json('meeting_days')->nullable(); $t->time('start_time')->nullable(); $t->time('end_time')->nullable();
            $t->string('timezone')->default('Africa/Cairo');
        });
        Schema::create('academy_group_lessons', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedBigInteger('group_id');
            $t->unsignedInteger('standardLink_id'); $t->unsignedInteger('created_by');
            $t->date('lesson_date'); $t->time('start_time'); $t->string('timezone')->default('Africa/Cairo');
            $t->string('topic', 255)->nullable(); $t->boolean('homework_assigned')->default(false); $t->timestamps();
            $t->unique(['group_id', 'lesson_date', 'start_time'], 'academy_group_lesson_slot');
            $t->index(['school_id', 'group_id', 'lesson_date']);
        });
        Schema::create('academy_lesson_students', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedBigInteger('lesson_id'); $t->unsignedInteger('student_id');
            $t->string('attendance')->default('pending'); $t->string('homework')->default('not_assigned');
            $t->unsignedInteger('recorded_by')->nullable(); $t->timestamps();
            $t->unique(['lesson_id', 'student_id']); $t->index(['school_id', 'student_id', 'lesson_id']);
        });
        Schema::create('academy_student_messages', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedInteger('student_id');
            $t->unsignedInteger('teacher_id'); $t->text('message'); $t->timestamps();
            $t->index(['school_id', 'student_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_student_messages'); Schema::dropIfExists('academy_lesson_students'); Schema::dropIfExists('academy_group_lessons');
        Schema::table('academy_group_settings', fn (Blueprint $t) => $t->dropColumn(['meeting_days', 'start_time', 'end_time', 'timezone']));
    }
};
