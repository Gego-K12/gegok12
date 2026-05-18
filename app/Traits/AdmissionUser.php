<?php
/**
 * Trait for processing RegisterUser
 */
namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\StudentParentLink;
use App\Models\StudentAcademic;
use App\Models\TeacherProfile;
use App\Models\ParentProfile;
use App\Models\Userprofile;
use Illuminate\Support\Str;
use App\Models\FeePayment;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 *
 * @class trait
 * Trait for RegisterUser Processes
 */
trait AdmissionUser
{
    /**
     * Create a new student with profile, academics, and optional fee payment.
     *
     * @param object $data Request payload containing student details
     * @param int $usergroup_id User group ID for the student
     * @param int $standardLink_id Standard link to associate
     * @param string $path Avatar path if provided
     * @param mixed $fee Fee model or data required for payment
     * @param \App\Models\User $admin Admin performing the operation
     * @return \App\Models\User Newly created student user
     */
    public function CreateStudent($data, $usergroup_id,$standardLink_id,$path,$fee,$admin)
    {
        \DB::beginTransaction();
        try
        {            
            $user = new User;
            $user->school_id    = $data->school_id;
            $user->usergroup_id = $usergroup_id;

            /*if(!is_null($data->name))
            {
                $user->name = $data->name;
            }*/

            $user->password                 = bcrypt('password'); //demo 
            $user->email                    = null;
            $user->mobile_no                = null;
            $user->email_verification_code  = Str::random(40);
            $user->registration_number      = date('YmdHis');

            $user->save();
            
            $userprofile = new Userprofile;

            $userprofile->school_id     = $data->school_id;
            $userprofile->user_id       = $user->id;
            $userprofile->usergroup_id  = $usergroup_id;
            $userprofile->firstname     = $data->name;
            if(!is_null($data->lastname))
            {
                $userprofile->lastname = $data->lastname;
            }
            $userprofile->gender                = $data->gender;
            
            $userprofile->date_of_birth         = date('Y-m-d',strtotime($data->date_of_birth));
            
            $userprofile->blood_group           = $data->blood_group;
            
            $userprofile->address               = $data->permanent_address;

            if(!is_null($data->birth_place))
            {
                $userprofile->birth_place = $data->birth_place;
            }

            if(!is_null($data->native_place))
            {
                $userprofile->native_place = $data->native_place;
            }

            if(!is_null($data->mother_tongue))
            {
                $userprofile->mother_tongue = $data->mother_tongue;
            }

            if(!is_null($data->community))
            {
                $userprofile->caste = $data->community;
            }

            if(!is_null($data->sub_caste))
            {
                $userprofile->sub_caste=$data->sub_caste;
            }
            
            if(!is_null($data->aadhar_number))
            {
                $userprofile->aadhar_number  = $data->aadhar_number;
            }

            //$userprofile->joining_date          = date('Y-m-d',strtotime($data->joining_date));
            
            $userprofile->registration_number = $user->registration_number;
            
            if(!is_null($data->EMIS_number))
            {
                $userprofile->EMIS_number = $data->EMIS_number;
            }
            if(!is_null($data->notes))
            {
                $userprofile->notes = $data->notes;
            } 

            if($path != '')
            {
                $userprofile->avatar = $path;              
            }
            else
            {
                if($userprofile->gender == 'male')
                {
                    $userprofile->avatar = "uploads/male.png";
                }
                elseif ($userprofile->gender == 'female') 
                {
                    $userprofile->avatar = "uploads/female.png";
                }
                else
                {
                    $userprofile->avatar = "uploads/images.jpg";
                }
            }

            $userprofile->save();

            $academic = new StudentAcademic;

            $academic->school_id                    = $data->school_id;
            $academic->academic_year_id             = $data->academic_year_id;
            $academic->user_id                      = $user->id;
            $academic->standardLink_id              = $standardLink_id;
            $academic->roll_number                  = $user->registration_number;
            $academic->id_card_number               = $user->registration_number;
            $academic->board_registration_number    = $data->board_registration_number;
            $academic->mode_of_transport            = $data->mode_of_transport;
            $academic->transport_details            = is_array($data->transport_details) ? $data->transport_details : null;

            $academic->siblings                     = $data->siblings;
            $academic->siblings_count               = $data->siblings_count;
            if($data->siblings == 'yes')
            { 
                $array=[];

                for($i = 0 ; $i < $data->siblings_count ; $i++)
                {
                    $array[$i]['sibling_relation']      =  $data->sibling_relation[$i]; 
                    $array[$i]['sibling_name']          =  $data->sibling_name[$i];
                    $array[$i]['sibling_date_of_birth'] =  date('Y-m-d',strtotime($data->sibling_date_of_birth[$i])); 
                    $array[$i]['sibling_standard']      =  $data->sibling_standard[$i];     
                }
                
                $academic->sibling_details  =   $array;
            }
            $academic->height               = $data->height;
            $academic->weight               = $data->weight;
            $academic->medication_problems  = $data->medical_details;

            $academic->save();
            if(config('gfee.enabled', false))
            {

                if($data->payment_status == 'paid')
                {
                    $feepayment = new \Gegok12\Fee\Models\FeePayment;

                    $feepayment->fee_id           = $fee->id;
                    $feepayment->user_id          = $user->id;
                    $feepayment->paid_amount      = $fee->amount;
                    $feepayment->paid_on          = date('Y-m-d');
                    $feepayment->notify_parent    = '1';
                    $feepayment->status           = '1';
                    $feepayment->created_by       = $admin->id;
                    $feepayment->updated_by       = $admin->id;

                    $feepayment->save();
                }
                else
                {
                    $feepayment = new \Gegok12\Fee\Models\FeePayment;

                    $feepayment->fee_id           = $fee->id;
                    $feepayment->user_id          = $user->id;
                    $feepayment->status           = 0;

                    $feepayment->save();
                }
            }
            \DB::commit();
            return $user;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }


    /**
     * Create or link a father profile to a student.
     *
     * @param int|null $student_id Student identifier to link (optional)
     * @param object $data Request payload containing father details
     * @param int $usergroup_id User group ID for parent role
     * @return \App\Models\User Newly created father user
     */
    public function CreateStudentFather($student_id,$data , $usergroup_id)
    {
        \DB::beginTransaction();
        try
        {    
            $user = new User;

            $user->school_id    = $data->school_id;
            $user->usergroup_id = $usergroup_id;
            /*if(!is_null($data->father_name))
            {
                $user->name = $data->father_name;
            }*/
            $user->password = bcrypt('password'); //demo 
            $user->email = $data->father_email;
            $user->mobile_no = $data->father_mobile_no;
            $user->email_verification_code = Str::random(40);

            $user->save();
                
            $userprofile = new Userprofile;

            $userprofile->school_id         = $data->school_id;
            $userprofile->user_id           = $user->id;
            $userprofile->usergroup_id      = $usergroup_id;
            $userprofile->firstname         = $data->father_name;
            if(!is_null($data->mother_mobile_no))
            {
                $userprofile->alternate_no  = $data->mother_mobile_no;
            }

            $userprofile->save();
            
            $parent = new ParentProfile;

            $parent->school_id          =   $data->school_id;
            $parent->user_id            =   $user->id;
            $parent->qualification_id   =   $data->father_qualification_id;
            $parent->profession         =   $data->father_occupation;
            $parent->sub_occupation     =   $data->sub_occupation ?? null;
            $parent->designation        =   $data->father_designation;
            $parent->organization_name  =   $data->father_organisation;
            //$parent->official_address   =   $data->official_address;
            $parent->relation           =   'father';
            $parent->annual_income      =   $data->father_income;

            $parent->save();

            if($student_id != NULL)
            {
                $student = User::where('id',$student_id)->first();
            }
            else
            {
                $student = User::where('email',$data->mother_email)->orWhere('email',$data->father_email)->first();
            }

            $link = new StudentParentLink;

            $link->school_id  = $data->school_id;
            $link->parent_id  = $user->id;
            $link->student_id = $student->id;
            $link->status     = 1;

            $link->save();
            
            \DB::commit();
            return $user;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }

    /**
     * Create or link a mother profile to a student.
     *
     * @param int|null $student_id Student identifier to link (optional)
     * @param object $data Request payload containing mother details
     * @param int $usergroup_id User group ID for parent role
     * @return \App\Models\User Newly created mother user
     */
    public function CreateStudentMother($student_id,$data , $usergroup_id)
    {
        \DB::beginTransaction();
        try
        {     
            $user = new User;

            $user->school_id    = $data->school_id;
            $user->usergroup_id = $usergroup_id;
            /*if(!is_null($data->mother_name))
            {
                $user->name = $data->mother_name;
            }*/
            $user->password = bcrypt('password'); //demo 
            $user->email = $data->mother_email;
            $user->mobile_no = $data->mother_mobile_no;
            $user->email_verification_code = Str::random(40);

            $user->save();
                
            $userprofile = new Userprofile;

            $userprofile->school_id         = $data->school_id;
            $userprofile->user_id           = $user->id;
            $userprofile->usergroup_id      = $usergroup_id;
            $userprofile->firstname         = $data->mother_name;
            if(!is_null($data->mother_mobile_no))
            {
                $userprofile->alternate_no  = $data->mother_mobile_no;
            }

            $userprofile->save();
                
            $parent = new ParentProfile;

            $parent->school_id          =   $data->school_id;
            $parent->user_id            =   $user->id;
            $parent->qualification_id   =   $data->mother_qualification_id;
            $parent->profession         =   $data->mother_occupation;
            $parent->sub_occupation     =   $data->sub_occupation ?? null;
            $parent->designation        =   $data->mother_designation;
            $parent->organization_name  =   $data->mother_organisation;
            //$parent->official_address   =   $data->official_address;
            $parent->relation           =   'mother';
            $parent->annual_income      =   $data->mother_income;

            $parent->save();
            
            if($student_id != NULL)
            {
                $student = User::where('id',$student_id)->first();
            }
            else
            {
                $student = User::where('name',$data->ref_name)->first();
            }

            $link = new StudentParentLink;

            $link->school_id  = $data->school_id;
            $link->parent_id  = $user->id;
            $link->student_id = $student->id;
            $link->status     = 1;

            $link->save();
            
            \DB::commit();
            return $user;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }
}
