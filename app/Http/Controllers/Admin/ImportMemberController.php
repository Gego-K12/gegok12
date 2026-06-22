<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportMemberRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentFormatExport;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Standard;
use App\Imports\UsersImport;
use App\Traits\LogActivity;
use App\Traits\Common;
use League\Csv\Writer;
use App\Models\User;
use Exception;

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
 *
 * @package App\Http\Controllers\Admin
 */
class ImportMemberController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display the member import page.
     *
     * @return \Illuminate\View\View
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
     * @param  \App\Http\Requests\ImportMemberRequest  $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function importUsers(ImportMemberRequest $request)
    {
        // 
        try
        {
            Excel::import(new UsersImport, $request->file('import_file'));

            $count = \Session::get('count');
            if ($count != 0)
            {
                return back()->with('failmessage', 'You can add only ' . $count . ' Members');
            }

            \Session::forget('count'); 
             
            $insertedcount = \Session::get('insertedcount');
            if ($insertedcount > 0)
            {
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
                    $insertedcount . ' ' . trans('messages.insert_success_msg')
                );
            }
            else
            {
                return back()->with('failmessage', trans('messages.insert_failure_msg'));
            }

            \Session::forget('insertedcount'); 
        }
        catch (Exception $e)
        {
            //dd($e->getMessage());
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
     * @param  \Illuminate\Http\Request  $request
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
