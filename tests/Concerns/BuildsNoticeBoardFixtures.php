<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Concerns;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Standard;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\Subject;
use App\Models\Teacherlink;
use App\Models\User;

/**
 * Builds the school/academic-year/class fixture graph needed by notice
 * board visibility tests. There are no factories for AcademicYear,
 * Standard, Section, or Subject in this codebase, so these are created
 * directly with the minimum required columns.
 */
trait BuildsNoticeBoardFixtures
{
    /**
     * SchoolObserver::created() already creates a "current" (status=1) and
     * "previous" (status=0) academic year for every new school, since
     * SiteHelper::getAcademicYear() always resolves to that pair. Creating
     * another one here would just be an orphaned, unused row.
     */
    protected function createActiveAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    protected function createStandardLink(School $school, AcademicYear $year, User $classTeacher): StandardLink
    {
        $standard = Standard::create([
            'school_id' => $school->id,
            'name' => 'Grade '.random_int(1, 12),
            'slug' => 'grade-'.uniqid(),
            'status' => 1,
        ]);

        $section = Section::create([
            'school_id' => $school->id,
            'name' => 'Section '.chr(random_int(65, 90)),
            'status' => 1,
        ]);

        return StandardLink::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'class_teacher_id' => $classTeacher->id,
            'standard_id' => $standard->id,
            'section_id' => $section->id,
            'status' => 1,
        ]);
    }

    protected function linkTeacherToSubject(User $teacher, StandardLink $link, School $school, AcademicYear $year): Teacherlink
    {
        $subject = Subject::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'standard_id' => $link->standard_id,
            'section_id' => $link->section_id,
            'name' => 'Subject '.uniqid(),
            'type' => 'core',
            'status' => 1,
        ]);

        return Teacherlink::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'standardLink_id' => $link->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'status' => 1,
        ]);
    }

    protected function enrollStudent(User $student, StandardLink $link, School $school, AcademicYear $year): StudentAcademic
    {
        return StudentAcademic::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $student->id,
            'standardLink_id' => $link->id,
        ]);
    }
}
