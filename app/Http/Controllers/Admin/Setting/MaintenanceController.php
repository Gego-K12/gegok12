<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Traits\SettingProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class MaintenanceController
 *
 * Controller for maintenance mode and related site-status settings.
 */
class MaintenanceController extends Controller
{
    use SettingProcess;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('admin.settings.maintenancesettings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $maintenance = '0';
        $register = '0';
        $login_status = '0';

        if ($request->maintenance == 1) {
            $maintenance = $request->maintenance;
        }
        if ($request->register == 1) {
            $register = $request->register;
        }
        if ($request->login_status == 1) {
            $login_status = $request->login_status;
        }

        $this->updatesettings('maintenance', $maintenance);
        $this->updatesettings('register', $register);
        $this->updatesettings('login_status', $login_status);

        return redirect()->back();
    }
}
