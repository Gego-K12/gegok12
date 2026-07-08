<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\PayrollTemplateRequest;
use App\Http\Requests\Payroll\TemplateUpdateRequest;
use App\Http\Resources\Payroll\TemplateListResource;
use App\Http\Resources\Payroll\TemplateResource;
use App\Models\PayCategory;
use App\Models\PayrollItem;
use App\Models\PayrollTemplate;
use App\Models\TemplateItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class PayrollTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('accountant/payroll/template/index');
    }

    public function showlist()
    {
        $template = PayrollTemplate::with('user')->get();
        $template = TemplateListResource::collection($template);

        return $template;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('accountant/payroll/template/create');

    }

    public function list()
    {
        $array = [];
        $item = PayrollItem::get();
        $category = PayCategory::get();
        $array['heading'] = $item;
        $array['category'] = $category;

        return $array;
    }

    public function itemlist($id)
    {
        $templateitem = TemplateItem::where([['template_id', $id], ['paycategory_id', '!=', 1]])->get();

        return $templateitem;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PayrollTemplateRequest $request)
    {
        \DB::beginTransaction();
        try {

            $template = new PayrollTemplate;
            $template->school_id = Auth::user()->school_id;
            $template->name = $request->name;
            $template->status = $request->status;
            $template->created_by = Auth::id();
            $template->save();
            for ($i = 0; $i < $request->payrollscount; $i++) {
                $headings = 'head_id'.$i;
                $category_id = 'category_id'.$i;
                $category_value = 'category_value'.$i;
                $templateitem = new TemplateItem;
                $templateitem->template_id = $template->id;
                $templateitem->item_id = $request->$headings;
                $templateitem->paycategory_id = $request->$category_id;
                if ($request->$category_id == 4) {
                    $templateitem->category_value = $request->$category_value;
                }
                $templateitem->save();
            }
            $res['success'] = 'Template added successfully';

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('accountant/payroll/template/edit', ['templateid' => $id]);
    }

    public function editshow($id)
    {
        return new TemplateResource(PayrollTemplate::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(TemplateUpdateRequest $request, $id)
    {
        \DB::beginTransaction();
        try {

            $template = PayrollTemplate::find($id);
            $template->name = $request->name;
            $template->status = $request->status;
            $template->created_by = Auth::id();
            $template->save();
            $template->payrollitems()->delete();
            for ($i = 0; $i < $request->payrollscount; $i++) {
                $headings = 'head_id'.$i;
                $category_id = 'category_id'.$i;
                $category_value = 'category_value'.$i;
                $templateitem = new TemplateItem;
                $templateitem->template_id = $template->id;
                $templateitem->item_id = $request->$headings;
                $templateitem->paycategory_id = $request->$category_id;
                if ($request->$category_id == 4) {
                    $templateitem->category_value = $request->$category_value;
                }
                $templateitem->save();
            }
            $res['success'] = 'Template updated successfully';

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $template = PayrollTemplate::find($id);
        /*if(count($template->salaries())>0)
        {
          $res['message'] = 'Template used at many salary structure';
        }else{*/
        $template->payrollitems()->delete();
        $template->delete();
        $res['message'] = 'Template deleted successfully';

        // }
        return $res;
    }
}
