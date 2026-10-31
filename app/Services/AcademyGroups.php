<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** Uses the existing classroom/enrollment tables so learning screens stay connected. */
class AcademyGroups
{
    public function create(int $academyId, array $data): Group
    {
        return DB::transaction(function () use ($academyId, $data) {
            $academy = DB::table('schools')->where('id', $academyId)->whereNull('deleted_at')->lockForUpdate()->first();
            abort_unless($academy, 404);
            if (!empty($data['standards_link_id'])) {
                $link = DB::table('standards_link')->where('school_id', $academyId)->where('id', $data['standards_link_id'])
                    ->whereNull('deleted_at')->first();
                abort_unless($link, 404);
                $linkId = $link->id;
            } else {
                $period = app(AcademyContext::class)->prepare($academyId);
                $teacher = DB::table('users')->where('id', $data['teacher_id'])->where('school_id', $academyId)
                    ->where('usergroup_id', 5)->where('status', 'active')->whereNull('deleted_at')->first();
                abort_unless($teacher, 422, 'Choose an active teacher from this academy.');
                $level = (int) $data['level'];
                if ($level < 1 || $level > 6) {
                    throw ValidationException::withMessages(['level' => 'Choose a level from 1 to 6.']);
                }
                $standard = DB::table('standards')->where('school_id', $academyId)
                    ->where('slug', 'academy-'.$academyId.'-level-'.$level)->whereNull('deleted_at')->first();
                $standardId = $standard?->id ?: DB::table('standards')->insertGetId([
                    'school_id' => $academyId, 'name' => 'Level '.$level, 'slug' => 'academy-'.$academyId.'-level-'.$level,
                    'order' => $level, 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
                ]);
                $section = DB::table('sections')->where('school_id', $academyId)->where('name', $data['group_name'])->whereNull('deleted_at')->first();
                $sectionId = $section?->id ?: DB::table('sections')->insertGetId([
                    'school_id' => $academyId, 'name' => $data['group_name'], 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
                ]);
                if (Group::forAcademy($academyId)->where('group_name', $data['group_name'])->exists()) {
                    throw ValidationException::withMessages(['group_name' => 'A group with this name already exists.']);
                }
                $linkId = DB::table('standards_link')->insertGetId([
                    'school_id' => $academyId, 'academic_year_id' => $period->id, 'class_teacher_id' => $teacher->id,
                    'standard_id' => $standardId, 'section_id' => $sectionId, 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
                ]);
                foreach (range(1, 2) as $unit) {
                    $subject = DB::table('subjects')->where('school_id', $academyId)->where('academic_year_id', $period->id)
                        ->where('standard_id', $standardId)->where('section_id', $sectionId)->where('name', 'Unit '.$unit)->whereNull('deleted_at')->first();
                    $subjectId = $subject?->id ?: DB::table('subjects')->insertGetId([
                        'school_id' => $academyId, 'academic_year_id' => $period->id, 'standard_id' => $standardId,
                        'section_id' => $sectionId, 'name' => 'Unit '.$unit, 'type' => 'core', 'status' => 1,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                    DB::table('class_teacher_links')->insert([
                        'school_id' => $academyId, 'academic_year_id' => $period->id, 'standardLink_id' => $linkId,
                        'subject_id' => $subjectId, 'teacher_id' => $teacher->id, 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
            if (Group::where('standardLink_id', $linkId)->whereRaw('LOWER(group_name) = ?', [mb_strtolower($data['group_name'])])->exists()) {
                throw ValidationException::withMessages(['group_name' => 'This group already exists.']);
            }
            $group = Group::create(['group_name' => $data['group_name'], 'standardLink_id' => $linkId]);
            $yearId = DB::table('standards_link')->where('id', $linkId)->value('academic_year_id');
            DB::afterCommit(function () use ($academyId, $yearId) {
                \Illuminate\Support\Facades\Cache::forget('standardLink'.$academyId.':'.$yearId);
                \Illuminate\Support\Facades\Cache::forget('standardLink'.$academyId);
            });
            return $group;
        });
    }

    public function enroll(int $academyId, int $groupId, array $studentIds): void
    {
        DB::transaction(function () use ($academyId, $groupId, $studentIds) {
            $group = Group::forAcademy($academyId)->whereKey($groupId)->lockForUpdate()->firstOrFail();
            $link = DB::table('standards_link')->where('id', $group->standardLink_id)->where('school_id', $academyId)->first();
            $ids = array_values(array_unique(array_map('intval', $studentIds)));
            sort($ids);
            foreach ($ids as $id) {
                $student = DB::table('users')->where('id', $id)->where('school_id', $academyId)
                    ->where('usergroup_id', 6)->where('status', 'active')->whereNull('deleted_at')->lockForUpdate()->first();
                if (!$student) {
                    throw ValidationException::withMessages(['selectedUsers' => 'Choose active students from this academy only.']);
                }
                $member = DB::table('group_members')->where('group_id', $groupId)->where('member_id', $id)->where('member_type', 'student')->first();
                if ($member) {
                    DB::table('group_members')->where('id', $member->id)->update(['deleted_at' => null, 'updated_at' => now()]);
                } else {
                    DB::table('group_members')->insert(['group_id' => $groupId, 'member_id' => $id, 'member_type' => 'student', 'created_at' => now(), 'updated_at' => now()]);
                }
                if (!config('academy_backend.enabled')) {
                    continue;
                }
                $current = DB::table('student_academics')->where('school_id', $academyId)->where('user_id', $id)
                    ->where('academic_year_id', $link->academic_year_id)->whereNull('deleted_at')->orderByDesc('id')->first();
                if (!$current || (int) $current->standardLink_id !== (int) $link->id) {
                    // Preserve prior enrollment rows as history rather than overwriting them.
                    DB::table('student_academics')->where('school_id', $academyId)->where('user_id', $id)
                        ->where('academic_year_id', $link->academic_year_id)->whereNull('deleted_at')->update(['deleted_at' => now(), 'updated_at' => now()]);
                    $oldGroups = Group::forAcademy($academyId)->where('standardLink_id', '!=', $link->id)
                        ->whereHas('standardlink', fn ($q) => $q->where('academic_year_id', $link->academic_year_id))->pluck('id');
                    DB::table('group_members')->whereIn('group_id', $oldGroups)->where('member_id', $id)
                        ->where('member_type', 'student')->whereNull('deleted_at')->update(['deleted_at' => now(), 'updated_at' => now()]);
                    DB::table('student_academics')->insert([
                        'school_id' => $academyId, 'academic_year_id' => $link->academic_year_id, 'user_id' => $id,
                        'standardLink_id' => $link->id, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $previousLink = $current?->standardLink_id;
                    DB::afterCommit(function () use ($link, $previousLink) {
                        foreach (array_filter([$link->id, $previousLink]) as $id) {
                            \Illuminate\Support\Facades\Cache::forget('class_students_'.$id);
                            \Illuminate\Support\Facades\Cache::forget('class_student_count'.$id);
                        }
                    });
                }
            }
        });
    }
}
