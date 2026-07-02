<?php
/**
 * Trait for processing AcademicProcess
 */
namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Traits\EventProcess;
use App\Models\StandardLink;
use App\Models\Teacherlink;
use App\Models\Attendance;
use App\Models\Standard;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TempTimetable;
use App\Models\User;
use Exception;

/**
 *
 * @class trait
 * Trait for AcademicProcess Processes
 */
trait AcademicProcess
{
    use EventProcess;

    /**
     * Bulk create default standards for a school based on level selection.
     *
     * @param int $school_id School identifier
     * @param object $data Request payload containing standards scope
     * @return \App\Models\Standard Last created standard model
     */
    public function addStandard($school_id , $data)
    {
        try
        {
            $nursery            = ['prekg','lkg','ukg'];
            $primary            = ['1','2','3','4','5'];
            $secondary          = ['6','7','8','9','10'];
            $higher_secondary   = ['11','12'];

            if($data->standards == 'nursery')
            {
                $list = $nursery;
            }
            elseif($data->standards == 'primary')
            {
                $list = array_merge($nursery,$primary);
            }
            elseif($data->standards == 'secondary')
            {
                $list = array_merge($nursery,$primary,$secondary);
            }
            else
            {
                $list = array_merge($nursery,$primary,$secondary,$higher_secondary);
            }

            for($i = 0 ; $i < count($list) ; $i++)
            {
                $standard = new Standard;

                $standard->school_id    =   $school_id;
                $standard->name         =   strtolower($list[$i]);
                $standard->order        =   $i;
                $standard->status       =   1;

                $standard->save();
            }

            return $standard;
        }
        catch(Exception $e)
        {
            \Log::error($e->getMessage());
        }
    }

    /**
     * Insert a single standard in relation to a reference standard order.
     *
     * @param int $school_id School identifier
     * @param object $data Request payload containing standard name, reference id, and position
     * @return \App\Models\Standard Newly created standard
     */
    public function createStandard($school_id , $data)
    {
        try
        {
            $standard = new Standard;

            $standard->school_id    =   $school_id;
            $standard->name         =   strtolower($data->standard);
            $ref_standard = Standard::where('id',$data->ref_standard_id)->first();
            if($data->position == 'before')
            {
                $value = $ref_standard->order-1;
            }
            elseif($data->position == 'after')
            {
                $value = $ref_standard->order+1;
            }
            $standard->order        =   $value;
            $standard->status       =   1;

            $standard->save();

            return $standard;
        }
        catch(Exception $e)
        {
        }
    }

    /**
     * Create a section for a school.
     *
     * @param int $school_id School identifier
     * @param object $data Request payload containing section name
     * @return \App\Models\Section Newly created section
     */
    public function createSection($school_id , $data)
    {
        try
        {
            $section = new Section;

            $section->school_id    =   $school_id;
            $section->name         =   ucfirst($data->section);
            $section->status       =   1;

            $section->save();

            return $section;
        }
        catch(Exception $e)
        {
        }
    }

    /**
     * Create a subject within an academic year and link to standard/section.
     *
     * @param int $school_id School identifier
     * @param int $academic_year_id Academic year identifier
     * @param object $data Request payload containing subject details
     * @return \App\Models\Subject Newly created subject
     */
    public function createSubject($school_id , $academic_year_id , $data)
    {
        try
        {
            $subject = new Subject;

            $subject->school_id         =   $school_id;
            $subject->academic_year_id  =   $academic_year_id;
            $subject->standard_id       =   $data->subject_standard_id;
            $subject->section_id        =   $data->subject_section_id;
            $subject->name              =   ucfirst($data->subject);
            $subject->code              =   $data->code;
            $subject->type              =   $data->type;
            $subject->status            =   1;

            $subject->save();

            return $subject;
        }
        catch(Exception $e)
        {
        }
    }

    /**
     * Create a standard link and related teacher assignments.
     *
     * @param int $school_id School identifier
     * @param int $academic_year_id Academic year identifier
     * @param object $data Request payload including class teacher, subjects, and counts
     * @return \App\Models\StandardLink Newly created standard link
     */
    public function createStandardLink($school_id , $academic_year_id , $data)
    {
        DB::beginTransaction();
        try
        {
            $standardLink = new StandardLink;

            $standardLink->school_id        = $school_id;
            $standardLink->academic_year_id = $academic_year_id;
            $standardLink->class_teacher_id = $data->class_teacher_id;
            $standardLink->standard_id      = $data->standard_id;
            $standardLink->no_of_students   = $data->no_of_students;
            if( ($data->standard_name == '11') || ($data->standard_name == '12') )
            {
                if($data->stream == 'others')
                {
                    $standardLink->stream           = $data->other_stream;
                }
                else
                {
                    $standardLink->stream           = $data->stream;
                }
            }
            $standardLink->section_id       = $data->section_id;
            $standardLink->status           = 1;

            $standardLink->save();

            $class_teacher = User::where('id',$standardLink->class_teacher_id)->first();

            $class_teacher->addRole('student_leave_checker');

            for($i=0 ; $i<$data->count ; $i++)
            {
                $sub        = 'subject_id'.$i;
                $teacher    = 'teacher_id'.$i;
                $subject_type = 'subject_type'.$i;

                $subject = Subject::where('id',$data->$sub)->first();
                $teacherlink = new Teacherlink;
                $teacherlink->school_id         = $school_id;
                $teacherlink->academic_year_id  = $academic_year_id;
                $teacherlink->standardLink_id   = $standardLink->id;
                $teacherlink->subject_id        = $subject->id;
                $teacherlink->teacher_id        = $data->$teacher;
                $teacherlink->subject_type      = $data->$subject_type;

                $teacherlink->save();
            }
            DB::commit();
            return $standardLink;
        }
        catch(Exception $e)
        {
            DB::rollBack();
            \Log::error($e->getMessage());
        }
    }

    /**
     * Update a standard link and refresh assigned teachers/subjects.
     *
     * @param int $school_id School identifier
     * @param int $academic_year_id Academic year identifier
     * @param int $standardLink_id Standard link identifier
     * @param object $data Request payload including teacher/subject updates
     * @return \App\Models\StandardLink Updated standard link
     */
    public function editStandardLink($school_id , $academic_year_id , $standardLink_id , $data)
    {
        DB::beginTransaction();
        try
        {
            $standardLink = StandardLink::where('id',$standardLink_id)->first();

            if($standardLink->class_teacher_id != $data->class_teacher_id)
            {
                $old_class_teacher = User::where('id',$standardLink->class_teacher_id)->first();

                $old_class_teacher->removeRole('student_leave_checker');

                $class_teacher = User::where('id',$data->class_teacher_id)->first();

                $class_teacher->addRole('student_leave_checker');
            }
            else
            {
                $class_teacher = User::where('id',$standardLink->class_teacher_id)->first();
                if(!$class_teacher->hasRole('student_leave_checker'))
                {
                    $class_teacher->addRole('student_leave_checker');
                }
            }

            $standardLink->class_teacher_id = $data->class_teacher_id;
            $standardLink->no_of_students   = $data->no_of_students;
            if( ($data->standard == '11') || ($data->standard == '12') )
            {
                if($data->stream == 'others')
                {
                    $standardLink->stream           = $data->other_stream;
                }
                else
                {
                    $standardLink->stream           = $data->stream;
                }
            }

            $standardLink->save();

            $teacherlinks = Teacherlink::where('standardLink_id',$standardLink_id)->get();


            foreach($teacherlinks as $teacher)
            {
                if(class_exists('Gegok12\Timetable\Models\TempTimetable'))//new
                {
                    $temptimetable = \Gegok12\Timetable\Models\TempTimetable::where([['standardLink_id',$standardLink_id],['subject_id',$teacher->subject_id],['teacher_id',$teacher->teacher_id]])->delete();
                }

                $teacher->delete();

            }

            for($i=0 ; $i<$data->count ; $i++)
            {
                $sub        = 'subject_id'.$i;
                $teacher    = 'teacher_id'.$i;
                $subject_type = 'subject_type'.$i;
                $no_of_periods = 'no_of_periods'.$i;


                $subject = Subject::where('id',$data->$sub)->first();

                $teacherlink = new Teacherlink;

                $teacherlink->school_id         = $school_id;
                $teacherlink->academic_year_id  = $academic_year_id;
                $teacherlink->standardLink_id   = $standardLink->id;
                $teacherlink->subject_id        = $subject->id;
                $teacherlink->teacher_id        = $data->$teacher;
                $teacherlink->subject_type      = $data->$subject_type;
                $teacherlink->no_of_periods     = $data->$no_of_periods;


                $teacherlink->save();

               /* $timetabledelete=TempTimetable::where([['standardLink_id',$standardLink->id],['teacher_id',$data->$teacher],['subject_id',$subject->id]])->delete();*/


            }
            DB::commit();
            return $standardLink;
        }
        catch(Exception $e)
        {
            DB::rollBack();
        }
    }

    /**
     * Record attendance for students and trigger notifications for absentees.
     *
     * @param int $school_id School identifier
     * @param int $academic_year_id Academic year identifier
     * @param int $admin Admin user ID recording attendance
     * @param object $data Request payload with absent/present lists and session info
     * @return \App\Models\Attendance Last saved attendance record
     */
    public function createAttendance($school_id , $academic_year_id , $admin , $data)
    {
        DB::beginTransaction();
        try
        {
            for($i=0 ; $i < $data->absentCount ; $i++)
            {
                $student    = 'user_id'.$i;
                $reason     = 'reason_id'.$i;
                $remarks    = 'remarks'.$i;

                $attendance = new Attendance;

                $attendance->school_id          = $school_id;
                $attendance->academic_year_id   = $academic_year_id;
                $attendance->standardLink_id    = $data->standardLink_id;
                $attendance->date               = date('Y-m-d',strtotime($data->date));
                $attendance->session            = $data->session;
                $attendance->user_id            = $data->$student;
                $attendance->reason_id          = $data->$reason;
                $attendance->remarks            = $data->$remarks;
                $attendance->status             = 0;
                $attendance->recorded_by        = $admin;

                $attendance->save();
                $student = User::where('id',$data->$student)->first();
                foreach ($student->parents as $parent)
                {
                    $array=[];

                    $array['school_id']  = $school_id;
                    $array['user_id']    = $parent->userParent->id;
                    $array['message']    = 'Dear Parent, Kindly make a note that your child '.$student->FullName.' is absent for school today('.ucfirst($data->session).').';
                    $array['type']       = 'private message';

                    event(new SinglePushEvent($array));


                    $this->sendToAttendanceReminder($school_id,$attendance->date,$parent->userParent->id,$parent->userParent->mobile_no,$parent->userParent->email,$student->FullName);
                }

                    $datas = [];
                    //$child = User::where('id',$student->id)->first();
                    $datas['user']       =   $student;
                    $datas['type']       =   'attendance';
                    $datas['details']    =   'Dear Parent, Kindly make a note that your child '.$student->FullName.' is absent for school today('.ucfirst($data->session).').';
                    event(new SingleNotificationEvent($datas));

            }

            for($i=0 ; $i < $data->presentCount ; $i++)
            {
                $student    = 'present_id'.$i;
                if($data->$student != 'false')
                {
                    $attendance = new Attendance;

                    $attendance->school_id          = $school_id;
                    $attendance->academic_year_id   = $academic_year_id;
                    $attendance->standardLink_id    = $data->standardLink_id;
                    $attendance->date               = date('Y-m-d',strtotime($data->date));
                    $attendance->session            = $data->session;
                    $attendance->user_id            = $data->$student;
                    $attendance->status             = 1;
                    $attendance->recorded_by        = $admin;

                    $attendance->save();
                }
            }
            DB::commit();
            return $attendance;
        }
        catch(Exception $e)
        {
            DB::rollBack();
        }
    }

    /**
     * Record attendance for staff members and notify absent staff.
     *
     * @param int $school_id School identifier
     * @param int $academic_year_id Academic year identifier
     * @param int $admin Admin user ID recording attendance
     * @param object $data Request payload with absent/present staff data
     * @return \App\Models\Attendance Last saved attendance record
     */
    public function createStaffAttendance($school_id , $academic_year_id , $admin , $data)
    {
        DB::beginTransaction();
        try
        {
            for($i=0 ; $i < $data->absentCount ; $i++)
            {
                $staff    = 'user_id'.$i;
                $reason     = 'reason_id'.$i;
                $remarks    = 'remarks'.$i;

                $attendance = new Attendance;

                $attendance->school_id          = $school_id;
                $attendance->academic_year_id   = $academic_year_id;
                $attendance->date               = date('Y-m-d',strtotime($data->date));
                $attendance->session            = $data->session;
                $attendance->user_id            = $data->$staff;
                $attendance->reason_id          = $data->$reason;
                $attendance->remarks            = $data->$remarks;
                $attendance->status             = 0;
                $attendance->recorded_by        = $admin;

                $attendance->save();

                $staff = User::where('id',$data->$staff)->first();

                    $array=[];

                    $array['school_id']  = $school_id;
                    $array['user_id']    = $staff->id;
                    $array['message']    = 'Dear staff,'.$staff->FullName.' absent today.';
                    $array['type']       = 'private message';

                    event(new SinglePushEvent($array));

                    $this->sendToAttendanceReminder($school_id,$attendance->date,$staff->id,$staff->mobile_no,$staff->email,$staff->FullName);
            }

            for($i=0 ; $i < $data->presentCount ; $i++)
            {
                $staff    = 'present_id'.$i;
                if($data->$staff != 'false')
                {
                    $attendance = new Attendance;

                    $attendance->school_id          = $school_id;
                    $attendance->academic_year_id   = $academic_year_id;
                    $attendance->date               = date('Y-m-d',strtotime($data->date));
                    $attendance->session            = $data->session;
                    $attendance->user_id            = $data->$staff;
                    $attendance->status             = 1;
                    $attendance->recorded_by        = $admin;

                    $attendance->save();
                }
            }
            DB::commit();
            return $attendance;
        }
        catch(Exception $e)
        {
            DB::rollBack();
        }
    }
}
