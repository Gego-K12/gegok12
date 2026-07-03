<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Exports\StudentFormatExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportMemberRequest;
use App\Imports\UsersImport;
use App\Models\Standard;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ImportMemberController
 *
 * Handles bulk member import operations in the admin panel.
 *
 * Responsibilities:
 * - Display import page
 * - Import members via Excel/CSV
 * - Validate import data
 * - Log import activities
 * - Provide downloadable CSV import format
 */
class ImportMemberController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display the member import page.
     *
     * @return View
     */
    public function index()
    {
        //
        return view('admin/member/import/import');
    }

    /**
     * Import users from an uploaded Excel or CSV file.
     *
     * Handles:
     * - Import execution
     * - Import limit validation
     * - Success and failure messaging
     * - Activity logging
     *
     * @return RedirectResponse|null
     */
    public function importUsers(ImportMemberRequest $request)
    {
        //
        try {
            Excel::import(new UsersImport, $request->file('import_file'));

            $count = \Session::get('count');
            if ($count != 0) {
                return back()->with('failmessage', 'You can add only '.$count.' Members');
            }

            \Session::forget('count');

            $insertedcount = \Session::get('insertedcount');
            if ($insertedcount > 0) {
                $message = trans('messages.import_success_msg', ['module' => 'Student']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_IMPORT_STUDENT,
                    $message
                );

                return back()->with(
                    'successmessage',
                    $insertedcount.' '.trans('messages.insert_success_msg')
                );
            } else {
                return back()->with('failmessage', trans('messages.insert_failure_msg'));
            }

            \Session::forget('insertedcount');
        } catch (Exception $e) {
        }
    }

    /**
     * Download the sample CSV format for member import.
     *
     * Generates a CSV file containing:
     * - Required column headers
     * - Example values and hints
     *
     * Also logs the download activity.
     *
     * @return void
     */
    public function downloadFormat(Request $request)
    {
        $classes = Standard::orderBy('name')
            ->pluck('name')
            ->toArray();

        return Excel::download(
            new StudentFormatExport($classes),
            'School_Plus_Add_Student_Format.xlsx'
        );
        // $message = 'Downloaded Sample Format File Successfully';

        // $ip = $this->getRequestIP();
        // $this->doActivityLog(
        //     Auth::user(),
        //     Auth::user(),
        //     ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
        //     LOGNAME_DOWNLOAD_SAMPLE_FORMAT,
        //     $message
        // );
    }
}
