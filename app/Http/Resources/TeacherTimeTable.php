<?php

namespace App\Http\Resources;

use App\Helpers\SiteHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TeacherTimeTable extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->teacherlink as $teacherlink) {
            $array['standardLink'][] = $teacherlink->standardLink_id;
            $max = max($array['standardLink']);
            $array['subjects'][$teacherlink->standardLink_id] = $teacherlink->subject->name;
        }

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        if (class_exists('Gegok12\Timetable\Models\Timetable')) {
            $timetables = \Gegok12\Timetable\Models\Timetable::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->whereIn('standardLink_id', $array['standardLink'])->get();

            foreach ($timetables as $timetable) {
                if (ucfirst($timetable->day) == date('l')) {
                    foreach ($timetable->schedule as $schedules) {
                        foreach ($schedules as $key => $value) {
                            if ($key == 'subject_id') {
                                foreach ($array as $heading => $data) {
                                    if ($heading == 'subjects') {
                                        for ($i = 1; $i <= $max; $i++) {
                                            if ($data[$i] == $value) {
                                                if ($i == $timetable->standardLink_id) {
                                                    $common['periodCount'] = count($timetable->schedule);
                                                    $common[$i][$timetable->standardLink->StandardSection] = $schedules;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $common;
        }
    }
}
