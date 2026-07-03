<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionAddRequest;
use App\Http\Requests\PromotionImportRequest;
use App\Imports\PromotionImport;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Standard;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Log;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class PromotionController
 *
 * Handles student promotion workflow including:
 * - Promotion dashboard data
 * - CSV export of promotion list
 * - Importing promotion results
 */
class PromotionController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display promotion dashboard data.
     *
     * Prepares:
     * - Current & next academic year
     * - Current & next standard links
     * - Exam list (if enabled)
     *
     * @return array
     */
    public function index()
    {
        //
        $standardLink = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        $next_standardLink = [];

        $curr_academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $standards = Standard::where('school_id', Auth::user()->school_id)
            ->orderBy('order')
            ->pluck('id')
            ->toArray();

        $next_academic_year = AcademicYear::where([
            ['school_id', Auth::user()->school_id],
            ['status', 2],
        ])->first();

        if (count($standards) > 0) {
            $standard = implode(',', $standards);

            $standardLinks = StandardLink::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $next_academic_year->id],
            ])
                ->orderByRaw('FIELD(standard_id,'.$standard.')')
                ->orderBy('section_id')
                ->groupBy(['standard_id', 'section_id'])
                ->get();

            foreach ($standardLinks as $key => $standard) {
                $next_standardLink[$key]['id'] = $standard->id;
                $next_standardLink[$key]['standard_section'] = $standard->StandardSection;
            }

            $count = count($standardLinks);

            if (class_exists('Gegok12\Alumni\Models\Alumniprofile')) {
                $next_standardLink[$count + 1]['id'] = 'alumni';
                $next_standardLink[$count + 1]['standard_section'] = 'Alumni';
            }
        }

        if (class_exists('Gegok12\Exam\Models\Exam')) {
            $exam = \Gegok12\Exam\Models\Exam::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $curr_academic_year->id],
                ['exam_type', 'final'],
            ])->get();

            $exam = \Gegok12\Exam\Http\Resources\Exam::collection($exam)
                ->groupBy('standard_id');
        }

        $array = [];

        $array['curr_academic_yearlist'] = $curr_academic_year;
        $array['next_academic_yearlist'] = $next_academic_year;
        $array['next_standardLinklist'] = $next_standardLink;
        $array['standardLinklist'] = $standardLink;

        if (config('gexam.enabled', false)) {
            $array['examlist'] = $exam;
        }

        return $array;
    }

    /**
     * Show promotion creation page.
     *
     * @return View
     */
    public function create()
    {
        //
        return view('/admin/promotion/create');
    }

    /**
     * Export student promotion list as CSV.
     *
     * Generates CSV with:
     * - Roll number
     * - Student name
     * - Academic status
     * - Comments
     *
     * @return array
     */
    public function export(PromotionAddRequest $request)
    {
        try {
            $standardLink = StandardLink::find($request->curr_standardlink_id);
            $standard_name = $standardLink->StandardSection;

            if (class_exists('Gegok12\Exam\Models\Exam')) {
                $exam = \Gegok12\Exam\Models\Exam::where('id', $request->exam_id)
                    ->where('standard_id', $request->curr_standardlink_id)
                    ->first();
            }

            $users = User::where('school_id', Auth::user()->school_id)
                ->ByRole(6)
                ->whereHas('studentAcademic', function ($q) use ($standardLink) {
                    $q->where('standardLink_id', $standardLink->id);
                })
                ->get();

            if (count($users) == 0) {
                return ['success' => 'No Students Found'];
            }

            $filename = 'promotion/promotionlist_'.
                        $standard_name.
                        date('d-m-Y_H_i_s').
                        '.csv';

            $handle = fopen('php://temp', 'r+');

            fputcsv($handle, ['roll_number', 'student_name', 'academic_status', 'comments']);

            foreach ($users as $user) {
                $studentAcademic = StudentAcademic::where([
                    ['user_id', $user->id],
                    ['school_id', Auth::user()->school_id],
                    ['academic_year_id', $request->curr_academic_year_id],
                ])->first();

                $row = [
                    $studentAcademic->roll_number ?? '',
                    $user->userprofile->firstname.' '.$user->userprofile->lastname,
                    'P',
                    '',
                ];

                fputcsv($handle, $row);
            }

            rewind($handle);
            $csvData = stream_get_contents($handle);
            fclose($handle);

            $path = $this->fileUpload($filename, $csvData);
            $fileUrl = $this->getFilePath($filename);

            return [
                'success' => true,
                'path' => $fileUrl,
            ];

            $message = 'Student Promotionlist Exported Successfully';
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_STUDENT_PROMOTION,
                $message
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Import promotion results from uploaded file.
     *
     * Uses Excel import to process promotion data,
     * logs activity, and returns insert status.
     *
     * @return array|null
     */
    public function import(PromotionImportRequest $request)
    {
        try {
            \Session::put('exam_id', $request->exam_id);
            \Session::put('curr_academic_year_id', $request->curr_academic_year_id);
            \Session::put('next_academic_year_id', $request->next_academic_year_id);
            \Session::put('curr_standardlink_id', $request->curr_standardlink_id);
            \Session::put('next_standardlink_id', $request->next_standardlink_id);

            $path = $request->file('promotion_file');
            Excel::import(new PromotionImport, $path);

            $insertedcount = \Session::get('insertedcount');

            if ($insertedcount > 0) {
                $message = 'Student Promotion Details Imported Successfully';

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_IMPORT_STUDENT_PROMOTION,
                    $message
                );

                $res['success'] = $insertedcount.' '.trans('messages.insert_success_msg');
            } else {
                $res['success'] = trans('messages.insert_failure_msg');
            }

            \Session::forget('insertedcount');
            \Session::forget('exam_id');
            \Session::forget('curr_academic_year_id');
            \Session::forget('next_academic_year_id');
            \Session::forget('curr_standardlink_id');
            \Session::forget('next_standardlink_id');

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
