<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;

class SandboxController extends Controller
{
    public function getUGDegree()
    {
        $qualifications = SiteHelper::getUGList();

        return response()->json([
            'success' => true,
            'message' => 'UG Degree List',
            'data' => $qualifications,
        ], 200);
    }

    public function getPGDegree()
    {
        $qualifications = SiteHelper::getPGList();

        return response()->json([
            'success' => true,
            'message' => 'PG Degree List',
            'data' => $qualifications,
        ], 200);
    }

    public function getCertificate()
    {
        $qualifications = SiteHelper::getAdditionalCertificates();

        return response()->json([
            'success' => true,
            'message' => 'Additional Certificate List',
            'data' => $qualifications,
        ], 200);
    }

    public function getMaritalStatus()
    {
        $maritalStatus = SiteHelper::getMaritalList();

        return response()->json([
            'success' => true,
            'message' => 'Martial Status List',
            'data' => $maritalStatus,
        ], 200);
    }

    public function getDesignation()
    {
        // $designation = SiteHelper::getDesignations();
        $designation[0]['id'] = 'assistant_teacher';
        $designation[0]['name'] = 'Assistant Teacher';
        $designation[1]['id'] = 'co-ordinator';
        $designation[1]['name'] = 'Co-ordinator';
        $designation[2]['id'] = 'head_of_the_department';
        $designation[2]['name'] = 'Head Of The Department';
        $designation[3]['id'] = 'librarian';
        $designation[3]['name'] = 'Librarian';
        $designation[4]['id'] = 'others';
        $designation[4]['name'] = 'Others';
        $designation[5]['id'] = 'principal';
        $designation[5]['name'] = 'Principal';
        $designation[6]['id'] = 'teacher';
        $designation[6]['name'] = 'Teacher';
        $designation[7]['id'] = 'senior_teacher';
        $designation[7]['name'] = 'Senior Teacher';
        $designation[8]['id'] = 'vice_principal';
        $designation[8]['name'] = 'Vice Principal';

        return response()->json([
            'success' => true,
            'message' => 'Designation List',
            'data' => $designation,
        ], 200);
    }
}
