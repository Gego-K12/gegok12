<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $indexes = [
        'users' => ['academy_users_lookup', ['school_id', 'usergroup_id', 'status', 'deleted_at']],
        'standards_link' => ['academy_groups_lookup', ['school_id', 'academic_year_id', 'status']],
        'student_academics' => ['academy_enrollment_lookup', ['school_id', 'academic_year_id', 'user_id', 'deleted_at']],
        'homeworks' => ['academy_homework_lookup', ['school_id', 'academic_year_id', 'standardLink_id', 'date']],
        'attendances' => ['academy_attendance_lookup', ['school_id', 'academic_year_id', 'user_id', 'date']],
        'class_teacher_links' => ['academy_teacher_groups_lookup', ['school_id', 'academic_year_id', 'teacher_id', 'deleted_at']],
        'group_members' => ['academy_members_lookup', ['group_id', 'member_id', 'member_type', 'deleted_at']],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => [$name, $columns]) {
            if (Schema::hasTable($table) && !Schema::hasIndex($table, $name)) {
                Schema::table($table, fn (Blueprint $blueprint) => $blueprint->index($columns, $name));
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => [$name]) {
            if (Schema::hasTable($table) && Schema::hasIndex($table, $name)) {
                Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropIndex($name));
            }
        }
    }
};
