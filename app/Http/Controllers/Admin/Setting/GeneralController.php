<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingGeneralRequest;
use App\Traits\Common;
use App\Traits\SettingProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class GeneralController
 *
 * Controller for general site settings (title, name, logo, favicon).
 */
class GeneralController extends Controller
{
    use Common;
    use SettingProcess;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.settings.generalsettings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) // SettingGeneralRequest
    {
        try {
            $this->updatesettings('sitetitle', $request->sitetitle);
            $this->updatesettings('sitename', $request->sitename);
            $this->updatesettings(
                'assignment_status',
                $request->has('assignment_status') ? 1 : 0
            );

            $this->updatesettings(
                'homework_status',
                $request->has('homework_status') ? 1 : 0
            );

            if (($request->sitelogo) == null) {
                $this->updatesettings('sitelogo', (\config::get('settings.sitelogo')));
            } else {
                $name = $request->sitelogo->getClientOriginalName();
                $sitelogopath = $this->uploadFile('uploads/settings', $request->sitelogo, $name);
                $this->updatesettings('sitelogo', $sitelogopath);
            }

            if (($request->favicon) == null) {
                $this->updatesettings('favicon', (\config::get('settings.favicon')));
            } else {
                $name = $request->favicon->getClientOriginalName();
                $faviconpath = $this->uploadFile('uploads/settings', $request->favicon, $name);
                $this->updatesettings('favicon', $faviconpath);
            }

            return redirect()->back()->with('success', 'Settings updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
