<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Imports\LibraryCardImport;
use App\Models\LibraryCard;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controller responsible for importing library cards and providing
 * a downloadable CSV format for sample imports.
 */
class LibraryImportController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display paginated list of library cards.
     *
     * @return View
     */
    public function index()
    {
        $libiarycards = LibraryCard::paginate(10);

        return view('library/import/listcarddeatils', ['libiarycards' => $libiarycards]);
    }

    /**
     * Show the import form for student library cards.
     *
     * @return View
     */
    public function create()
    {
        return view('library/import/studentscard');
    }

    /**
     * Import library cards from uploaded file (xlsx, csv, txt).
     *
     * @return RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv,txt',
        ]);

        $file = $request->file('import_file');

        Excel::import(new LibraryCardImport, $file);

        return back()->with('success', 'Library card data imported successfully.');
    }

    /**
     * Output a CSV sample format for library card import.
     *
     * The method writes CSV content directly to the output and logs the
     * download action. It does not return a value.
     *
     * @return void
     */
    public function downloadFormat()
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        $csv->insertOne(['card_number', 'book_limit', 'expiry_date', 'registration_number', 'employee_id']);
        $csv->insertOne([
            '23321211',
            '10',
            '31-05-2025',
            '5252525',
            '10001',
        ]);

        $csv->output('School Plus Add Librarycard Format'.date('_d-m-Y_H:i').'.csv');

        $message = 'Downloaded Sample Format File Successfully';

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_DOWNLOAD_SAMPLE_FORMAT_LIBRARYCARD,
            $message
        );
    }
}
