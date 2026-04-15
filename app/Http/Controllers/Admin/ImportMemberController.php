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
use Illuminate\Http\Request;
use App\Models\Subscription;
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
     * - CSV validation before import
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
        try
        {
            // ==========================================
            // STEP 1: VALIDATE CSV BEFORE IMPORTING
            // ==========================================
            
            $importer = new UsersImport;
            
            // Read the file into a collection for validation
            $rows = Excel::toCollection($importer, $request->file('import_file'))->first();
            
            // Skip the first row if it contains format hints (check if first row firstname = 'firstname')
            if ($rows->count() > 0 && strtolower(trim($rows->first()['firstname'] ?? '')) === 'firstname') {
                $rows = $rows->slice(1);
            }
            
            // Run validation
            $validationErrors = $importer->validateImport($rows);
            $validationWarnings = $importer->getImportWarnings();
            
            // If there are validation errors, return them to the user
            if (!empty($validationErrors)) {
                $errorMessages = $this->formatValidationErrors($validationErrors);
                return back()->with('validation_errors', $validationErrors)
                             ->with('validation_warnings', $validationWarnings)
                             ->with('failmessage', 'Import validation failed. Please fix the following errors in your CSV file:')
                             ->with('error_details', $errorMessages);
            }

            // ==========================================
            // STEP 2: PROCEED WITH IMPORT
            // ==========================================
            
            // Create a fresh importer instance for actual import
            $importerForExecution = new UsersImport;
            Excel::import($importerForExecution, $request->file('import_file'));
            
            // Get import summary statistics
            $importSummary = $importerForExecution->getImportSummary();

            $count = \Session::get('count');
            if ($count != 0)
            {
                return back()->with('failmessage', 'You can add only ' . $count . ' Members');
            }

            \Session::forget('count');

            $insertedcount = \Session::get('insertedcount');
            $parentsCreated = \Session::get('parents_created', 0);
            $siblingsLinked = \Session::get('siblings_linked', 0);
            
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

                // Build success response with import summary
                $successResponse = back()->with(
                    'successmessage',
                    $insertedcount . ' ' . trans('messages.insert_success_msg')
                )->with('import_summary', [
                    'students_imported' => $insertedcount,
                    'parents_created' => $parentsCreated,
                    'siblings_linked' => $siblingsLinked
                ]);
                
                \Session::forget('insertedcount');
                \Session::forget('parents_created');
                \Session::forget('siblings_linked');
                
                return $successResponse;
            }
            else
            {
                return back()->with('failmessage', trans('messages.insert_failure_msg'));
            }
        }
        catch (Exception $e)
        {
            \Log::error('Import Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            return back()->with('failmessage', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Format validation errors into a readable string/array for display.
     *
     * @param array $validationErrors
     * @return string
     */
    protected function formatValidationErrors(array $validationErrors)
    {
        $formatted = [];
        
        foreach ($validationErrors as $error) {
            $rowInfo = "Row {$error['row']}";
            if (!empty($error['student'])) {
                $rowInfo .= " ({$error['student']})";
            }
            $rowInfo .= ": " . implode('; ', $error['errors']);
            $formatted[] = $rowInfo;
        }
        
        return implode("\n", $formatted);
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
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne([
            'firstname','lastname','mobile_no','email','gender','date_of_birth',
            'blood_group','class','section','address','city','state','country',
            'pincode','birth_place','native_place','mother_tongue','caste',
            'sub_caste','aadhar_number','joining_date','admission_number',
            'EMIS_number','roll_number','id_card_number',
            'board_registration_number','mode_of_transport','driver_name',
            'driver_contact_number','siblings','siblings_count',
            'sibling_relation','sibling_name','sibling_date_of_birth',
            'sibling_class','notes','parent_firstname','parent_lastname',
            'parent_mobile_no','parent_alternate_no','parent_email',
            'parent_qualification','parent_occupation','parent_sub_occupation',
            'parent_designation','parent_organization_name',
            'parent_official_address','parent_annual_income','relation'
        ]);

        $csv->insertOne([
            'firstname','lastname','mobile_no','email','(male , female)',
            'date_of_birth',
            '(a+,a1+,b+,b1+,o+,ab+,a1b+,a-,a1-,b-,b1-,o-,ab-,a1b-)',
            'example : 1','(A,B)','address','city','state','country','pincode',
            'birth_place','native_place','mother_tongue',
            '(BC,BCM,FC,MBC,OBC,Others,SC,SCA,ST)',
            'sub_caste','aadhar_number','joining_date','admission_number',
            'EMIS_number','roll_number','id_card_number',
            '(only for 10th and 12th students)',
            '(auto,car,city_bus,cycle,rickshaw,school_bus,taxi,walking)',
            'if auto,rickshaw,taxi','if auto,rickshaw,taxi',
            '(yes,no)','siblings_count',
            'sibling_relation1,sibling_relation2,..',
            'sibling_name1,sibling_name2,..',
            'sibling_date_of_birth1,sibling_date_of_birth2,..',
            'sibling_standard1,sibling_standard2,..',
            'notes','parent_firstname','parent_lastname',
            'parent_mobile_no','parent_alternate_no','parent_email',
            'UG Degree,PG Degree',
            '(business,central_government_employee,private,home_maker,state_government_employee,others)',
            'enter if not home_maker','enter if not home_maker',
            'enter if not home_maker','enter if not home_maker',
            'enter if not home_maker','(father,mother,guardian)',
        ]);

        $csv->output('School Plus Add Student Format' . date('_d-m-Y_H:i') . '.csv');

        $message = 'Downloaded Sample Format File Successfully';

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_DOWNLOAD_SAMPLE_FORMAT,
            $message
        );
    }
}
