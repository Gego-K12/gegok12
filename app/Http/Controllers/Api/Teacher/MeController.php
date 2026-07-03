<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Models\Users\TeacherUser;
use Exception;
use Hash;
use Illuminate\Support\Facades\Auth;
use Log;

class MeController extends Controller
{
    //
    public function myInfo()
    {
        $user = TeacherUser::where('id', Auth::user()->id)->first();

        $details = $user->getTeacherDetails();

        $myInfo['fullname'] = $user->FullName;
        $myInfo['dateOfBirth'] = $user->userprofile->date_of_birth == '' ? null : date('d M Y', strtotime($user->userprofile->date_of_birth));
        $myInfo['employeeId'] = $details['employee_id'];
        $myInfo['designation'] = ucfirst($details['designation']);
        $myInfo['subDesignation'] = ($details['sub_designation'] != null ? ucfirst($details['sub_designation']) : '');
        $myInfo['mobileNo'] = $user->mobile_no;
        $myInfo['emailId'] = $user->email;
        $myInfo['gender'] = ucfirst($user->userprofile->gender);
        $myInfo['gender'] = ucfirst($user->userprofile->gender);
        $myInfo['bloodGroup'] = ucfirst($user->userprofile->blood_group);
        $myInfo['aadharNumber'] = $user->userprofile->aadhar_number;
        $myInfo['maritalStatus'] = $user->userprofile->marital_status == '' ? null : ucwords($user->userprofile->marital_status);
        $myInfo['avatar'] = $user->userprofile->AvatarPath;
        $myInfo['joiningDate'] = $user->userprofile->joining_date == '' ? null : date('d M Y', strtotime($user->userprofile->joining_date));

        $myInfo['ugDegree'] = $details['ug_degree'];
        $myInfo['pgDegree'] = $details['pg_degree'];
        $myInfo['specialization'] = ucfirst($details['specialization']);
        $myInfo['additionalCertificateId'] = $details['qualification_id'];
        $myInfo['otherAdditionalCertificateId'] = $details['sub_qualification'];

        $myInfo['notes'] = $user->userprofile->notes;

        $myInfo['address'] = $user->userprofile->address;
        $myInfo['country'] = ucfirst($user->userprofile->country->name);
        $myInfo['state'] = ucfirst($user->userprofile->state->name);
        $myInfo['city'] = ucfirst($user->userprofile->city->name);
        $myInfo['pincode'] = $user->userprofile->pincode;

        $myInfo['age'] = date('Y') - date('Y', strtotime(optional($user->userprofile)->date_of_birth));
        $myInfo['classTeacher'] = $user->standardLink->StandardSection;
        $myInfo['permissions'] = Auth::user()->getRoles();

        return response()->json([
            'success' => true,
            'message' => 'My Details',
            'data' => $myInfo,
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = User::where('id', Auth::id())->first();
            $hashedPassword = $user->password;
            if (Hash::check($request->oldpassword, $hashedPassword)) {
                $user->password = Hash::make($request->newpassword);
                $user->save();

                $success = true;
                $message = 'Password Updated Successfully';
                $error_code = 200;
            } else {
                $success = false;
                $message = 'Password Update Failed';
                $error_code = 302;
            }

            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $error_code);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
