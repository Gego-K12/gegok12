<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $users = [];
        foreach ($this->taskAssignee as $key => $taskAssignee) 
        {
            $users[$key]['user_name'] = $taskAssignee->user->name;
            $users[$key]['user'] = $taskAssignee->user->FullName;
        }

        if( date('Y-m-d H:i:s',strtotime($this->task_date)) <= date('Y-m-d H:i:s') )
        {
            $snooze = 1; 
        }
        else
        {
            $snooze = 0;
        }
        
        return [
            //
            'task_id'           =>  $this->id,
            'title'             =>  ucfirst($this->title),
            'to_do_list'        =>  $this->to_do_list,
            'task_date'         =>  date('d-m-Y H:i:s',strtotime($this->task_date)),
            'task_flag'         =>  $this->task_flag,
            'task_status'       =>  $this->task_status,
            'assignee'          =>  $this->type,
            'assignee_display'  =>  ucwords($this->type),
            'standardLink'      =>  $this->taskAssignee[0]['standardLink']['StandardSection'],
            'user_name'         =>  $users,
            'snooze'            =>  $snooze,
            'reminder'          =>  'Reminder On '.date('d-m-Y H:i:s',strtotime($this->reminder_date)),
            'reminder_date'     =>  $this->reminder_date,
            'auth_id'           =>  Auth::id(),
            'created_by'        =>  $this->user_id,
            'priority'          =>  $this->priority,
            'total_count'       =>  $this->taskAssignee->count(),
            'completion_count'  =>  $this->taskAssignee->where('status','completed')->count(),
        ];
    }
}