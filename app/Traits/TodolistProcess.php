<?php
/**
 * Trait for processing TodolistProcess
 */
namespace App\Traits;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\Notification\ClassNotificationEvent;
use App\Models\Users\StudentUser;
use App\Events\StandardPushEvent;
use App\Events\SinglePushEvent;
use App\Models\TaskAssignee;
use App\Traits\EventProcess;
use App\Models\Reminder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 *
 * @class trait
 * Trait for TodolistProcess Processes
 */
trait TodolistProcess
{
    use EventProcess;

    public function addTaskAssignee( $data , $school_id , $academic_year_id , $auth_id )
    { 
        \DB::beginTransaction();
        try
        {
            // dd($data);
            $today = date('Y-m-d H:i:s');

            $task                       =   new Task;

            $task->school_id            =   $school_id;
            $task->academic_year_id     =   $academic_year_id;
            $task->user_id              =   $auth_id;
            $task->type                 =   $data->assignee;
            $task->title                =   $data->title;
            $task->to_do_list           =   $data->to_do_list;
            $task->task_date            =   date('Y-m-d H:i:s',strtotime($data->task_date));
            $task->reminder             =   $data->reminder;
            $task->priority           =   $data->priority;
            if($data->reminder == 'others')
            {
                $task->reminder_date    =   date('Y-m-d H:i:s',strtotime($data->reminder_date));
            }
            
            if(date('Y-m-d',strtotime($task->task_date)) == date('Y-m-d'))
            {
                $task->task_flag = 1;
            }
            elseif($task->task_date > $today)
            {
                $task->task_flag = 2;
            }
            else
            {
                $task->task_flag = 0;
            }

            $task->save();

            if($task->reminder == 'others')
            {
                $reminder_date  = date('Y-m-d H:i:s',strtotime($task->reminder_date));
            }
            else
            {
                $reminder_date = $task->ReminderValue;
            }

            if($data->assignee == 'class')
            {
                foreach ($data->class_ids as $class_id) 
                {
                    $task_assignee = new TaskAssignee;

                    $task_assignee->task_id             = $task->id;
                    $task_assignee->standardLink_id     = $class_id;
                    $task_assignee->assigned_type       = 'class';

                    $task_assignee->save();


                    $this->addClassReminder($school_id,$reminder_date,$task->title,$task->id,$data->standardLink_id);

                    $data=[];

                    $data['school_id']      =   $school_id;
                    $data['standard_id']    =   $data->standardLink_id;
                    $data['message']        =   'New Task Assigned';
                    $data['type']           =   'task';

                    event(new StandardPushEvent($data));

                    $array = [];

                    $array['school_id']         = $school_id;
                    $array['standardLink_id']   = $data->standardLink_id;
                    $array['details']           = trans('notification.task_assign_msg');  

                    event(new ClassNotificationEvent($array));
                    }
            }
            elseif($data->assignee == 'student')
            {
                $standard_id = $data->standardLink_id;
                foreach ($data->selectedUsers as $student_id) 
                {
                    $task_assignee = new TaskAssignee;

                    $task_assignee->task_id         = $task->id;
                    $task_assignee->user_id         = $student_id;
                    $task_assignee->standardLink_id = $standard_id;

                    $task_assignee->save();

                    $student = User::where('id',$student_id)->first();

                    foreach ($student->parents as $parent) 
                    {
                        $array=[];

                        $array['school_id']  =   $school_id;
                        $array['user_id']    =   $parent->userParent->id;
                        $array['message']    =   'New Task Assigned';
                        $array['type']       =   'task';

                        event(new SinglePushEvent($array));

                        $this->sendToTaskReminder($school_id,$reminder_date,$task->title,$task->id,$parent->userParent->email,$parent->userParent->mobile_no);
                    }

                    $data = [];

                    $data['user']       =   $student;
                    $data['details']    =   trans('notification.task_assign_msg');

                    event(new SingleNotificationEvent($data));
                }
            }
            elseif($data->assignee == 'teacher')
            {
                foreach ($data->selectedTeachers as $teacher_id) 
                {
                    $task_assignee = new TaskAssignee;

                    $task_assignee->task_id     = $task->id;
                    $task_assignee->user_id     = $teacher_id;

                    $task_assignee->save();

                    $teacher = User::where('id',$teacher_id)->first();

                    $this->sendToTaskReminder($school_id,$reminder_date,$task->title,$task->id,$teacher->email,$teacher->mobile_no);

                    $array=[];

                    $array['school_id']  =   $school_id;
                    $array['user_id']    =   $teacher->id;
                    $array['message']    =   'New Task Assigned';
                    $array['type']       =   'task';

                    event(new SinglePushEvent($array));

                    $data = [];

                    $data['user']       =   $teacher;
                    $data['details']    =   trans('notification.task_assign_msg');

                    event(new SingleNotificationEvent($data));
                }
            }
            elseif($data->assignee == 'non_teaching')
            {
                foreach ($data->non_teachers as $teacher_id) 
                {
                    $task_assignee = new TaskAssignee;

                    $task_assignee->task_id     = $task->id;
                    $task_assignee->user_id     = $teacher_id;

                    $task_assignee->save();

                    $teacher = User::where('id',$teacher_id)->first();

                    $this->sendToTaskReminder($school_id,$reminder_date,$task->title,$task->id,$teacher->email,$teacher->mobile_no);

                    $array=[];

                    $array['school_id']  =   $school_id;
                    $array['user_id']    =   $teacher->id;
                    $array['message']    =   'New Task Assigned';
                    $array['type']       =   'task';

                    event(new SinglePushEvent($array));

                    $data = [];

                    $data['user']       =   $teacher;
                    $data['details']    =   trans('notification.task_assign_msg');

                    event(new SingleNotificationEvent($data));
                }
            }
            // elseif($data->assignee == 'group')
            // {
                
            // }
            else
            {
                $task_assignee = new TaskAssignee;

                $task_assignee->task_id     = $task->id;
                $task_assignee->user_id     = $auth_id;

                $task_assignee->save();

                $auth_user = User::where('id',$auth_id)->first();

                $this->sendToTaskReminder($school_id,$reminder_date,$task->title,$task->id,$auth_user->email,$auth_user->mobile_no);
            }
            
            \DB::commit();
            return $task;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }

    public function addClassReminder($school_id,$reminder_date,$title,$entity_id,$standardLink_id)
    {
        //
        $students = StudentUser::where('school_id',$school_id)->ByRole(6)->ByStandard($standardLink_id)->get();

        foreach ($students as $student) 
        {
            foreach ($student->parents as $parent) 
            {
                $this->sendToTaskReminder($school_id,$reminder_date,$title,$entity_id,$parent->userParent->email,$parent->userParent->mobile_no);
            }
        }
    }

    public function editTaskAssignee( $data , $auth_id , $id )
    { 
        \DB::beginTransaction();
        try
        {
            $today = date('Y-m-d H:i:s');

            $task                       =   Task::where('id',$id)->first();

            $task->type                 =   $data->assignee;
            $task->title                =   $data->title;
            $task->to_do_list           =   $data->to_do_list;
            $task->task_date            =   date('Y-m-d H:i:s',strtotime($data->task_date));
            $task->reminder             =   $data->reminder;
            if($data->reminder == 'others')
            {
                $task->reminder_date    =   date('Y-m-d H:i:s',strtotime($data->reminder_date));
            }
            if($task->task_date == $today)
            {
                $task->task_flag = 1;
            }
            elseif($task->task_date > $today)
            {
                $task->task_flag = 2;
            }
            else
            {
                $task->task_flag = 0;
            }

            $task->save();

            if($task->reminder == 'others')
            {
                $reminder_date  = date('Y-m-d H:i:s',strtotime($task->reminder_date));
            }
            else
            {
                $reminder_date = $task->ReminderValue;
            }

            $reminders = Reminder::where([['school_id',$task->school_id],['entity_id',$task->id],['entity_name','App\\Models\\Task']])->get();

            foreach ($reminders as $reminder) 
            {
                $reminder->delete();
            }

            if($data->assignee == 'class')
            {
                $task_assignee = TaskAssignee::where('task_id',$id)->first();

                if($task_assignee->standardLink_id == $data->standardLink_id)
                {
                    $task_assignee->save();

                    $data=[];

                    $data['school_id']      =   $task->school_id;
                    $data['standard_id']    =   $data->standardLink_id;
                    $data['message']        =   'Task Assigned Updated';
                    $data['type']           =   'task';

                    event(new StandardPushEvent($data));

                    $array = [];

                    $array['school_id']         = $task->school_id;
                    $array['standardLink_id']   = $data->standardLink_id;
                    $array['details']           = trans('notification.task_assign_update_msg');  

                    event(new ClassNotificationEvent($array));
                }
                else
                {
                    $task_assignee->delete();

                    $task_assignee_new                      = new TaskAssignee;

                    $task_assignee_new->task_id             = $task->id;
                    $task_assignee_new->standardLink_id     = $data->standardLink_id;
                    $task_assignee_new->status              = 1;

                    $task_assignee_new->save();

                    $data=[];

                    $data['school_id']      =   $task->school_id;
                    $data['standard_id']    =   $data->standardLink_id;
                    $data['message']        =   'Task Assigned Updated';
                    $data['type']           =   'task';

                    event(new StandardPushEvent($data));

                    $array = [];

                    $array['school_id']         = $task->school_id;
                    $array['standardLink_id']   = $data->standardLink_id;
                    $array['details']           = trans('notification.task_assign_update_msg');  

                    event(new ClassNotificationEvent($array));
                }

                $this->addClassReminder($task->school_id,$reminder_date,$task->title,$task->id,$data->standardLink_id);
            }
            elseif($data->assignee == 'student')
            {
                $task_assignees = TaskAssignee::where('task_id',$id)->get();
                foreach ($task_assignees as $task_assignee) 
                {
                    $task_assignee->delete();
                }

                $standard_id = $data->standardLink_id;
                foreach ($data->selectedUsers as $student_id) 
                {
                    $task_assignee = new TaskAssignee;

                    $task_assignee->task_id         = $task->id;
                    $task_assignee->user_id         = $student_id;
                    $task_assignee->standardLink_id = $standard_id;
                    $task_assignee->status          = 1;

                    $task_assignee->save();

                    $student = User::where('id',$student_id)->first();

                    foreach ($student->parents as $parent) 
                    {
                        $array=[];

                        $array['school_id']  =   $task->school_id;
                        $array['user_id']    =   $parent->userParent->id;
                        $array['message']    =   'Task Assigned Updated';
                        $array['type']       =   'task';

                        event(new SinglePushEvent($array));

                        $this->sendToTaskReminder($task->school_id,$reminder_date,$task->title,$task->id,$parent->userParent->email,$parent->userParent->mobile_no);
                    }

                    $data = [];

                    $data['user']       =   $student;
                    $data['details']    =   trans('notification.task_assign_update_msg');

                    event(new SingleNotificationEvent($data));
                }
            }
            elseif($data->assignee == 'teacher')
            {
                $task_assignees = TaskAssignee::where('task_id',$id)->get();
                foreach ($task_assignees as $task_assignee) 
                {
                    $task_assignee->delete();
                }

                foreach ($data->selectedTeachers as $teacher_id) 
                {
                    $task_assignee = new TaskAssignee;

                    $task_assignee->task_id     = $task->id;
                    $task_assignee->user_id     = $teacher_id;
                    $task_assignee->status      = 1;

                    $task_assignee->save();

                    $teacher = User::where('id',$teacher_id)->first();

                    $this->sendToTaskReminder($task->school_id,$reminder_date,$task->title,$task->id,$teacher->email,$teacher->mobile_no);

                    $array=[];

                    $array['school_id']  =   $task->school_id;
                    $array['user_id']    =   $teacher->id;
                    $array['message']    =   'Task Assigned Updated';
                    $array['type']       =   'task';

                    event(new SinglePushEvent($array));

                    $data = [];

                    $data['user']       =   $teacher;
                    $data['details']    =   trans('notification.task_assign_update_msg');

                    event(new SingleNotificationEvent($data));
                }
            }
            else
            {
                $task_assignees = TaskAssignee::where('task_id',$id)->get();
                foreach ($task_assignees as $task_assignee) 
                {
                    $task_assignee->delete();
                }

                $task_assignee = new TaskAssignee;

                $task_assignee->task_id     = $task->id;
                $task_assignee->user_id     = $auth_id;
                $task_assignee->status      = 1;

                $task_assignee->save();

                $auth_user = User::where('id',$auth_id)->first();

                $this->sendToTaskReminder($task->school_id,$reminder_date,$task->title,$task->id,$auth_user->email,$auth_user->mobile_no);
            }
            
            \DB::commit();
            return $task;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }

    public function snoozeTask( $data , $auth_id , $id )
    { 
        \DB::beginTransaction();
        try
        {
            $today      = date('Y-m-d H:i:s');
            $task       = Task::where('id',$id)->first();
            $task_date  = date('Y-m-d H:i:s',strtotime($task->task_date));
            $snooze_time = Carbon::now()->addSeconds(env('SNOOZE_TIME'))->format('Y-m-d H:i:s');

            if($today >= $task_date)
            {
                if($task->type == 'class')
                {
                    $task_assignee = TaskAssignee::where('task_id',$id)->first();

                    $students = User::where('school_id',$task->school_id)->ByRole(6)->ByStandard($task_assignee->standardLink_id)->get();

                    foreach ($students as $student) 
                    {
                        foreach ($student->parents as $parent) 
                        {
                            $this->sendToSnoozeTask($task->school_id,$snooze_time,$task->title,$task->id,$parent->userParent->email,$parent->userParent->mobile_no);
                        }
                        $this->sendToSnoozeTaskWeb($task->school_id,$snooze_time,$task->title,$task->id,$student->email,$student->mobile_no);
                    }
                }
                elseif($task->type == 'student')
                {
                    $task_assignees = TaskAssignee::where('task_id',$id)->get();
                    foreach ($task_assignees as $task_assignee) 
                    {
                        $student = User::where('id',$task_assignee->user_id)->first();

                        foreach ($student->parents as $parent) 
                        {
                            $this->sendToSnoozeTask($task->school_id,$snooze_time,$task->title,$task->id,$parent->userParent->email,$parent->userParent->mobile_no);
                        }
                        $this->sendToSnoozeTaskWeb($task->school_id,$snooze_time,$task->title,$task->id,$student->email,$student->mobile_no);
                    }
                }
                elseif($task->type == 'teacher')
                {
                    $task_assignees = TaskAssignee::where('task_id',$id)->get();
                    foreach ($task_assignees as $task_assignee) 
                    {
                        $teacher = User::where('id',$task_assignee->user_id)->first();

                        $this->sendToSnoozeTask($task->school_id,$snooze_time,$task->title,$task->id,$teacher->email,$teacher->mobile_no);
                        $this->sendToSnoozeTaskWeb($task->school_id,$snooze_time,$task->title,$task->id,$teacher->email,$teacher->mobile_no);
                    }
                }
                else
                {
                    $task_assignees = TaskAssignee::where('task_id',$id)->get();
                    foreach ($task_assignees as $task_assignee) 
                    {
                        $self = User::where('id',$task_assignee->user_id)->first();

                        $this->sendToSnoozeTask($task->school_id,$snooze_time,$task->title,$task->id,$self->email,$self->mobile_no);
                        $this->sendToSnoozeTaskWeb($task->school_id,$snooze_time,$task->title,$task->id,$self->email,$self->mobile_no);
                    }
                }
                $task->snooze = 1;
                
                $task->save();
            }
            
            \DB::commit();
            return $task;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }
}