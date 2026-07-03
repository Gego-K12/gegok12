<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ParentDetail as ParentDetailResource;
use App\Imports\HolidaysImport;
use App\Models\Events;
use App\Models\File;
use App\Models\Holiday;
use App\Models\Plan;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Reminder;
use App\Models\SalesOrder;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Users\TeacherUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use Excel;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use League\Csv\Writer;
use Log;

/**
 * Class ReportsController
 *
 * Handles all report-related operations such as:
 * - Holidays import/export
 * - Events reports
 * - Subscription reports
 * - Fee payment exports
 * - Student and teacher birthday exports
 * - Student status reports
 * - Parent reports
 * - Inventory stock, purchase, and sales reports
 */
class ReportsController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;

    /**
     * Display the reports dashboard.
     *
     * @return View
     */
    public function report(Request $request)
    {
        //
        $query = \Request::getQueryString();

        return view('/admin/reports/reports', ['query' => $query]);
    }

    /**
     * Download holiday CSV sample format.
     *
     * @return void
     */
    public function holidayFormat()
    {
        //
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        $csv->insertOne(['title', 'date']);

        $csv->output('School Plus Add Holiday Format'.date('_d-m-Y_H:i').'.csv');

        $message = ('Downloaded Sample Format File Successfully');

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_DOWNLOAD_HOLIDAY_SAMPLE_FORMAT,
            $message
        );
    }

    /**
     * Show holiday import page.
     *
     * @return View
     */
    public function holidayCreate()
    {
        //
        return view('/admin/reports/holidays');
    }

    /**
     * Import holidays from uploaded file.
     *
     * @return RedirectResponse|null
     */
    public function holidayImport(Request $request)
    {
        //
        try {
            Excel::import(new HolidaysImport, $request->file('import_file'));

            $insertedcount = \Session::get('insertedcount');
            if ($insertedcount > 0) {
                $message = trans('messages.import_success_msg', ['module' => 'Holiday']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_IMPORT_HOLIDAY,
                    $message
                );

                return back()->with('successmessage', $insertedcount.' '.trans('messages.insert_success_msg'));
            } else {
                return back()->with('failmessage', trans('messages.insert_failure_msg'));
            }
            \Session::forget('insertedcount');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export holidays as CSV.
     *
     * @return void
     */
    public function holidayExport(Request $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $holidays = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['category', 'holidays']])->orderBy('start_date', 'ASC')->get();
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($holidays) > 0) {
                $csv->insertOne(['title', 'date']);

                foreach ($holidays as $holiday) {
                    $csv->insertOne([
                        $holiday->title,
                        date('d-m-Y', strtotime($holiday->start_date)),
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Holiday Export'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Holiday Export'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Holiday']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_HOLIDAY,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display event reminder reports.
     *
     * @return View
     */
    public function eventReport()
    {
        //
        $event_reports = Reminder::where('school_id', Auth::user()->school_id)->where('entity_name', 'App\\Models\\Events')->get();

        return view('/admin/reports/events', ['event_reports' => $event_reports]);
    }

    /**
     * Display subscription reports.
     *
     * @return View
     */
    public function index()
    {
        //
        $subscription = Subscription::where('school_id', Auth::user()->school_id)->get();
        foreach ($subscriptions as $subscription) {
            $plan = Plan::where('id', $subscription->plan_id)->get();
        }

        return view('/admin/reports/index', ['subscriptions' => $subscription, 'plan' => $plan]);
    }

    /**
     * Filter subscriptions by date range.
     *
     * @return View
     */
    public function create(Request $request)
    {
        //
        $start = date('Y-m-d', strtotime($request->from_date));
        $end = date('Y-m-d', strtotime($request->to_date));

        $subscription = Subscription::where('school_id', Auth::user()->school_id)->Date($start, $end)->get();

        return view('/admin/reports/filter', ['subscriptions' => $subscription]);
    }

    /**
     * Show single subscription report.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        //
        $subscription = Subscription::where('id', $id)->first();
        if (Gate::allows('subscription', $subscription)) {
            return view('/admin/reports/show', ['subscriptions' => $subscription]);
        } else {
            abort(403);
        }
    }

    /**
     * Export fee payment details.
     *
     * @return void
     */
    public function exportFee(Request $request)
    {
        try {
            if (count($request->request) > 0) {
                $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'active');
            } else {
                $users = User::BySchool(Auth::user()->school_id)->ByRole(6)->ByStatus('active')->get();
            }
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($users) > 0) {
                $csv->insertOne(['roll_number', 'class', 'firstname', 'lastname', 'mobile_no', 'email', 'fee_title', 'term', 'amount', 'paid_on']);

                foreach ($users as $user) {
                    foreach ($user->feePayment as $feePayment) {
                        $csv->insertOne([
                            $user->studentAcademicLatest->roll_number,
                            $user->studentAcademicLatest->standardLink->StandardSection,
                            $user->userprofile->firstname,
                            $user->userprofile->lastname,
                            $user->mobile_no,
                            $user->email,
                            $feePayment->fee->name,
                            $feePayment->fee->term,
                            $feePayment->fee->amount,
                            date('d M Y', strtotime($feePayment->paid_on)),
                        ]);
                    }
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Student Export Fee Payment'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Student Export Fee Payment'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Fee Payment']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_FEE_PAYMENT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export student or teacher birthday list.
     *
     * @param  string  $type
     * @return void
     */
    public function exportBirthday($type)
    {
        try {
            if ($type == 'student') {
                $users = User::with('userprofile')->where('school_id', Auth::user()->school_id)->ByRole(6)->get();
                $csv = Writer::createFromFileObject(new \SplTempFileObject);

                if (count($users) > 0) {
                    $csv->insertOne(['roll_number', 'class', 'firstname', 'lastname', 'date_of_birth']);

                    foreach ($users as $user) {
                        $csv->insertOne([
                            $user->studentAcademicLatest->roll_number,
                            $user->studentAcademicLatest->standardLink->StandardSection,
                            $user->userprofile->firstname,
                            $user->userprofile->lastname,
                            date('d-m-Y', strtotime($user->userprofile->date_of_birth)),
                        ]);
                    }
                } else {
                    $csv->insertOne(['No Records Found']);
                    $csv->output('SP Student Export Birthday'.date('_d-m-Y_H:i').'.csv');
                }

                $csv->output('SP Student Export Birthday'.date('_d-m-Y_H:i').'.csv');

                $message = trans('messages.export_success_msg', ['module' => 'Student Birthday']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_EXPORT_BIRTHDAY_STUDENT,
                    $message
                );
            } elseif ($type == 'teacher') {
                $users = TeacherUser::with('userprofile')->where('school_id', Auth::user()->school_id)->ByRole(5)->get();
                $csv = Writer::createFromFileObject(new \SplTempFileObject);

                if (count($users) > 0) {
                    $csv->insertOne(['employee_id', 'designation', 'firstname', 'lastname', 'date_of_birth', 'mobile_no']);

                    foreach ($users as $user) {
                        $csv->insertOne([
                            $user->getTeacherDetails()['employee_id'],
                            $user->getTeacherDetails()['designation'] == 'others' ? $user->getTeacherDetails()['sub_designation'] : $user->getTeacherDetails()['designation'],
                            $user->userprofile->firstname,
                            $user->userprofile->lastname,
                            date('d-m-Y', strtotime($user->userprofile->date_of_birth)),
                            $user->mobile_no,
                        ]);
                    }
                } else {
                    $csv->insertOne(['No Records Found']);
                    $csv->output('SP Teacher Export Birthday'.date('_d-m-Y_H:i').'.csv');
                }

                $csv->output('SP Teacher Export Birthday'.date('_d-m-Y_H:i').'.csv');

                $message = trans('messages.export_success_msg', ['module' => 'Teacher Birthday']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_EXPORT_BIRTHDAY_TEACHER,
                    $message
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export active students report as CSV.
     *
     * Filters active students based on request parameters (if provided)
     * and exports their personal and academic details.
     *
     * @return void
     */
    public function exportActiveStudents(Request $request)
    {
        try {
            if (count($request->request) > 0) {
                $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'active');
            } else {
                $users = User::BySchool(Auth::user()->school_id)->ByRole(6)->ByStatus('active')->get();
            }
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($users) > 0) {
                $csv->insertOne(['firstname', 'lastname', 'gender', 'date_of_birth', 'class', 'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email']);

                foreach ($users as $user) {
                    $csv->insertOne([
                        $user->userprofile->firstname,
                        $user->userprofile->lastname,
                        $user->userprofile->gender,
                        date('d-m-Y', strtotime($user->userprofile->date_of_birth)),
                        $user->studentAcademicLatest->standardLink->StandardSection,
                        $user->userprofile->address,
                        $user->userprofile->city->name,
                        $user->userprofile->state->name,
                        $user->userprofile->country->name,
                        $user->userprofile->pincode,
                        $user->mobile_no,
                        $user->email,
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Export Active Student'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Export Active Student'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Active Students']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_ACTIVE_STUDENT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export exited students report as CSV.
     *
     * Exports students who have exited the school.
     *
     * @return void
     */
    public function exportExitStudents(Request $request)
    {
        try {
            if (count($request->request) > 0) {
                $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'exit');
            } else {
                $users = User::BySchool(Auth::user()->school_id)->ByRole(6)->ByStatus('exit')->get();
            }
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($users) > 0) {
                $csv->insertOne(['firstname', 'lastname', 'gender', 'date_of_birth', 'class', 'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email']);

                foreach ($users as $user) {
                    $csv->insertOne([
                        $user->userprofile->firstname,
                        $user->userprofile->lastname,
                        $user->userprofile->gender,
                        date('d-m-Y', strtotime($user->userprofile->date_of_birth)),
                        $user->studentAcademicLatest->standardLink->StandardSection,
                        $user->userprofile->address,
                        $user->userprofile->city->name,
                        $user->userprofile->state->name,
                        $user->userprofile->country->name,
                        $user->userprofile->pincode,
                        $user->mobile_no,
                        $user->email,
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Export Exit Student'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Export Exit Student'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Exit Student']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_EXIT_STUDENT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export suspended students report as CSV.
     *
     * @return void
     */
    public function exportSuspendedStudents(Request $request)
    {
        try {
            if (count($request->request) > 0) {
                $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'inactive');
            } else {
                $users = User::BySchool(Auth::user()->school_id)->ByRole(6)->ByStatus('inactive')->get();
            }
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($users) > 0) {
                $csv->insertOne(['firstname', 'lastname', 'gender', 'date_of_birth', 'class', 'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email']);

                foreach ($users as $user) {
                    $csv->insertOne([
                        $user->userprofile->firstname,
                        $user->userprofile->lastname,
                        $user->userprofile->gender,
                        date('d-m-Y', strtotime($user->userprofile->date_of_birth)),
                        $user->studentAcademicLatest->standardLink->StandardSection,
                        $user->userprofile->address,
                        $user->userprofile->city->name,
                        $user->userprofile->state->name,
                        $user->userprofile->country->name,
                        $user->userprofile->pincode,
                        $user->mobile_no,
                        $user->email,
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Export Suspended Student'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Export Suspended Student'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Suspended Student']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_SUSPENDED_STUDENT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export parent details report as CSV.
     *
     * @return void
     */
    public function exportParent(Request $request)
    {
        try {
            if (count($request->request) > 0) {
                $users = $this->ParentFilter($request, Auth::user()->school_id, 6);
            } else {
                $users = User::where('school_id', Auth::user()->school_id)->ByRole(6)->whereHas('userprofile', function ($q) {
                    $q->where('status', 'active')->orWhere('status', 'inactive');
                })->get();
                $users = ParentDetailResource::collection($users);
            }
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($users) > 0) {
                $csv->insertOne(['firstname', 'lastname', 'children', 'mobile_no', 'alternate_no', 'email', 'occupation', 'sub_occupation', 'designation', 'organization_name', 'address', 'relation', 'annual_income']);
                // 'qualification',
                foreach ($users as $user) {
                    foreach ($user->parents as $parent) {
                        $csv->insertOne([
                            $parent->userParent->userprofile->firstname,
                            $parent->userParent->userprofile->lastname,
                            $parent->userParent->getChildren(),
                            $parent->userParent->mobile_no,
                            $parent->userParent->userprofile->alternate_no,
                            $parent->userParent->email,
                            // $parent->userParent->getParentDetails()['qualification_name'],
                            $parent->userParent->getParentDetails()['profession'],
                            $parent->userParent->getParentDetails()['sub_occupation'],
                            $parent->userParent->getParentDetails()['designation'],
                            $parent->userParent->getParentDetails()['organization_name'],
                            $parent->userParent->getParentDetails()['official_address'],
                            $parent->userParent->getParentDetails()['relation'],
                            $parent->userParent->getParentDetails()['annual_income'],
                        ]);
                    }
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Parent Export'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Parent Export'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Parent']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_PARENT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export current inventory stock as CSV.
     *
     * Fetches product stock details from inventory module (if available)
     * or from default product model and exports them.
     *
     * @return void
     */
    public function currentstock()
    {
        try {
            if (class_exists('Gegok12\Inventory\Models\Product')) {
                $products = \Gegok12\Inventory\Models\Product::with('category', 'vendor')->where('school_id', Auth::user()->school_id)->get();
            } else {
                $products = Product::with('category', 'vendor')->where('school_id', Auth::user()->school_id)->get();
            }

            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($products) > 0) {
                $csv->insertOne(['product_name', 'category', 'vendor', 'quantity', 'purchased_date']);

                foreach ($products as $product) {
                    $csv->insertOne([
                        $product->name,
                        $product->category->name,
                        $product->vendor->name,
                        $product->quantity,
                        date('d M Y', strtotime($product->purchased_date)),
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Export Current Stock'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Export Current Stock'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Current Stock']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_CURRENT_STOCK,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export monthly purchase report as CSV.
     *
     * Generates purchase report for the given month and year.
     * Defaults to current month if not provided.
     *
     * @return void
     */
    public function monthlypurchase(Request $request)
    {
        try {
            $purchase_month = $request->month;
            $purchase_year = $request->year;

            if ($request->month == null) {
                $month = date('m');
                $year = date('Y');
                if (class_exists('Gegok12\Inventory\Models\PurchaseOrder')) { // new
                    $purchases = \Gegok12\Inventory\Models\PurchaseOrder::with('product')->where('school_id', Auth::user()->school_id)->whereMonth('purchased_date', $month)->whereYear('purchased_date', $year)->get();
                } else {                                                      // new
                    $purchases = PurchaseOrder::with('product')->where('school_id', Auth::user()->school_id)->whereMonth('purchased_date', $month)->whereYear('purchased_date', $year)->get();
                }
            } else {
                if (class_exists('Gegok12\Inventory\Models\PurchaseOrder')) { // new

                    $purchases = \Gegok12\Inventory\Models\PurchaseOrder::with('product')->where('school_id', Auth::user()->school_id)->whereMonth('purchased_date', $purchase_month)->whereYear('purchased_date', $purchase_year)->get();
                } else {
                    $purchases = PurchaseOrder::with('product')->where('school_id', Auth::user()->school_id)->whereMonth('purchased_date', $purchase_month)->whereYear('purchased_date', $purchase_year)->get();
                }
            }

            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($purchases) > 0) {
                $csv->insertOne(['product_name', 'quantity', 'price_per_item', 'purchased_date']);

                foreach ($purchases as $purchase) {
                    $csv->insertOne([
                        $purchase->product->name,
                        $purchase->quantity,
                        $purchase->price_per_item,
                        date('d-m-Y', strtotime($purchase->purchased_date)),
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Export Monthly Purchase'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Export Monthly Purchase'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Monthly Purchase']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_MONTHLY_PURCHASE,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Export monthly sales report as CSV.
     *
     * Generates sales report for the selected month and year.
     * Defaults to current month if parameters are missing.
     *
     * @return void
     */
    public function monthlysales(Request $request)
    {
        try {
            $sales_month = $request->month;
            $sales_year = $request->year;
            if (($sales_month == null) && ($sales_year == null)) {
                $month = date('m');
                $year = date('Y');

                if (class_exists('Gegok12\Inventory\Models\SalesOrder')) { // new
                    $sales = \Gegok12\Inventory\Models\SalesOrder::with('product', 'user')->where('school_id', Auth::user()->school_id)->whereMonth('sales_date', $month)->whereYear('sales_date', $year)->get();
                } else {                                              // new
                    $sales = SalesOrder::with('product', 'user')->where('school_id', Auth::user()->school_id)->whereMonth('sales_date', $month)->whereYear('sales_date', $year)->get();
                }
            } else {
                if (class_exists('Gegok12\Inventory\Models\SalesOrder')) { // new
                    $sales = \Gegok12\Inventory\Models\SalesOrder::with('product', 'user')->where('school_id', Auth::user()->school_id)->whereMonth('sales_date', $sales_month)->whereYear('sales_date', $sales_year)->get();
                } else {          // new
                    $sales = SalesOrder::with('product', 'user')->where('school_id', Auth::user()->school_id)->whereMonth('sales_date', $sales_month)->whereYear('sales_date', $sales_year)->get();
                }
            }

            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($sales) > 0) {
                $csv->insertOne(['product_name', 'standard', 'student', 'quantity', 'selling_price', 'sales_date', 'payment_status']);

                foreach ($sales as $sales) {
                    $csv->insertOne([
                        $sales->product->name,
                        $sales->user->studentAcademicLatest->standardLink->StandardSection,
                        $sales->user->userprofile->firstname,
                        $sales->quantity,
                        $sales->selling_price,
                        date('d-m-Y', strtotime($sales->sales_date)),
                    ]);
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output('SP Export Monthly sales'.date('_d-m-Y_H:i').'.csv');
            }

            $csv->output('SP Export Monthly sales'.date('_d-m-Y_H:i').'.csv');

            $message = trans('messages.export_success_msg', ['module' => 'Monthly sales']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_MONTHLY_SALES,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
