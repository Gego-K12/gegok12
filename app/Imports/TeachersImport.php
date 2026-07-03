<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\City;
use App\Models\Country;
use App\Models\Qualification;
use App\Models\State;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class TeachersImport
 *
 * Handles importing teacher data from an Excel file.
 * Creates teacher users along with their profile,
 * qualifications, and related metadata.
 *
 * The import:
 * - Respects subscription limits
 * - Prevents duplicate teachers
 * - Maps qualifications, location, and designation
 */
class TeachersImport implements ToCollection, WithHeadingRow
{
    use RegisterUser;

    /**
     * Process the imported Excel rows.
     *
     * For each row:
     * - Validates subscription limits
     * - Resolves country, state, city, and qualifications
     * - Creates a teacher user if not already existing
     *
     * Insert count and limits are stored in session.
     *
     * @return void
     */
    public function collection(Collection $rows)
    {
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = AcademicYear::where('school_id', $school_id)->where('status', 1)->first();
            $user_count = User::ByRole(6)->where('school_id', $school_id)->count();
            $subscription = Subscription::where('school_id', $school_id)->first();
            $count = $subscription->plan->no_of_members - $user_count;

            if ((count($rows) > 0) && (count($rows) <= ($count))) {
                foreach ($rows as $row) {
                    if ($row['firstname'] != null) {
                        $country = Country::where('name', 'LIKE', '%'.$row['country'].'%')->first();
                        $state = State::where('name', 'LIKE', '%'.$row['state'].'%')->first();
                        $city = City::where('name', 'LIKE', '%'.$row['city'].'%')->first();

                        $array = array_map('trim', str_getcsv($row['additional_coures']));
                        $qualification_array = [];

                        if ($row['additional_coures'] != null) {
                            for ($i = 0; $i < count($array); $i++) {
                                $arr[$i] = Qualification::where('type', 'teacher')
                                    ->where('display_name', 'LIKE', '%'.$array[$i].'%')
                                    ->pluck('id')
                                    ->toArray();

                                if ($arr[$i] != null) {
                                    $qualification_array[$i] = implode('', $arr[$i]);
                                }
                            }
                        }

                        $ug = Qualification::where('type', 'ug')
                            ->where('display_name', 'LIKE', '%'.$row['ug_degree'].'%')
                            ->pluck('id')
                            ->toArray();

                        if ($ug[0] == null) {
                            $ug_degree = 1;
                        }

                        $pg = Qualification::where('type', 'pg')
                            ->where('display_name', 'LIKE', '%'.$row['pg_degree'].'%')
                            ->pluck('id')
                            ->toArray();

                        if ($pg[0] == null) {
                            $pg_degree = 1;
                        }

                        $teacher = new Request;

                        $teacher->firstname = $row['firstname'];
                        $teacher->lastname = $row['lastname'];
                        $teacher->mobile_no = $row['mobile_no'];
                        $teacher->email = $row['email'];
                        $teacher->gender = $row['gender'];
                        $teacher->date_of_birth = date('Y-m-d', strtotime($row['date_of_birth']));
                        $teacher->blood_group = $row['blood_group'];
                        $teacher->standard = $standardLink->id;
                        $teacher->address = $row['address'];
                        $teacher->city_id = $city->id;
                        $teacher->state_id = $state->id;
                        $teacher->country_id = $country->id;
                        $teacher->pincode = $row['pincode'];
                        $teacher->aadhar_number = $row['aadhar_number'];
                        $teacher->joining_date = date('Y-m-d', strtotime($row['joining_date']));
                        $teacher->employee_id = $row['employee_id'];
                        $teacher->ug_degree = $ug_degree;
                        $teacher->pg_degree = $pg_degree;
                        $teacher->specialization = $row['specialization'];
                        $teacher->qualification_id = $qualification_array;
                        $teacher->sub_qualification = $row['sub_additional_coures'];
                        $teacher->designation = $row['designation'];
                        $teacher->notes = $row['notes'];

                        $mobile_no = $row['mobile_no'];
                        $employee_id = $row['employee_id'];
                        $avatar = '';

                        $user = User::where([
                            ['mobile_no', $mobile_no],
                            ['school_id', $school_id],
                        ])->orWhereHas('teacherprofile', function ($query) use ($employee_id) {
                            $query->where('employee_id', $employee_id);
                        })->exists();

                        if (! $user) {
                            $teacher = $this->CreateTeacher($teacher, $school_id, $academic_year, $avatar, 5);
                            $insertedcount++;
                        }
                    }
                }

                \Session::put('insertedcount', $insertedcount);
            } else {
                \Session::put('count', $count);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
