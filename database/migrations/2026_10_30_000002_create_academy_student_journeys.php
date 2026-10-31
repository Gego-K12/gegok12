<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academy_student_journeys', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedInteger('student_id')->unique();
            $t->string('placement_status')->default('pending'); $t->date('placement_date')->nullable();
            $t->text('placement_notes')->nullable(); $t->unsignedTinyInteger('level')->nullable();
            $t->unsignedTinyInteger('unit')->default(1); $t->timestamp('completed_at')->nullable(); $t->timestamps();
            $t->index(['school_id', 'placement_status']);
        });
        Schema::create('academy_group_settings', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedBigInteger('group_id')->unique();
            $t->unsignedTinyInteger('level'); $t->string('schedule', 500)->nullable(); $t->timestamps();
        });
        Schema::create('academy_learning_events', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedInteger('student_id')->nullable();
            $t->unsignedInteger('actor_id'); $t->string('event'); $t->text('details'); $t->timestamps();
            $t->index(['school_id', 'student_id', 'id']);
        });
        Schema::create('academy_teacher_feedback', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedInteger('student_id');
            $t->unsignedInteger('teacher_id'); $t->unsignedInteger('standardLink_id');
            $t->unsignedTinyInteger('level'); $t->unsignedTinyInteger('unit');
            $t->text('feedback'); $t->boolean('advances')->default(false); $t->timestamp('parent_read_at')->nullable(); $t->timestamps();
            $t->index(['school_id', 'student_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_teacher_feedback'); Schema::dropIfExists('academy_learning_events');
        Schema::dropIfExists('academy_group_settings'); Schema::dropIfExists('academy_student_journeys');
    }
};
