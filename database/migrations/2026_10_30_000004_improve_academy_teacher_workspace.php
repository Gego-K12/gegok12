<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academy_student_journeys', function (Blueprint $t) {
            $t->text('homework_drive_url')->nullable(); $t->text('recordings_url')->nullable(); $t->text('report_card_url')->nullable();
        });
        Schema::table('teacher_leave_applications', fn (Blueprint $t) => $t->string('academy_decision')->nullable());
        Schema::table('academy_group_settings', fn (Blueprint $t) => $t->boolean('is_demo')->default(false));
    }

    public function down(): void
    {
        Schema::table('academy_group_settings', fn (Blueprint $t) => $t->dropColumn('is_demo'));
        Schema::table('teacher_leave_applications', fn (Blueprint $t) => $t->dropColumn('academy_decision'));
        Schema::table('academy_student_journeys', fn (Blueprint $t) => $t->dropColumn(['homework_drive_url', 'recordings_url', 'report_card_url']));
    }
};
