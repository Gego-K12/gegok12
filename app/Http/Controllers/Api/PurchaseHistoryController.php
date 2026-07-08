<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HandlesGuzzleRequests;
use Exception;
use Illuminate\Http\Request;
use Log;

class PurchaseHistoryController extends Controller
{
    use HandlesGuzzleRequests;

    public function index(Request $request)
    {
        $api_url = env('ADDON_API_URL').'/api/addon/list';

        try {
            $user = User::where([['school_id', auth()->user()->school_id], ['usergroup_id', 3]])->first();
            $response = $this->guzzleGet($api_url, [
                'email' => $user->email,
                'domain_name' => request()->getHost(),
            ]);

            $addons = $response['data'];
            foreach ($addons as &$addon) {
                $addon['purchase_status'] = false;

                if (config('g'.$addon['slug'].'.enabled', false)) {
                    $addon['purchase_status'] = true;
                }
            }

            $response['data'] = $addons;

            // return $response;
            return response()->json([
                'success' => true,
                'message' => 'Addon Lists',
                'data' => $response['data'],
            ], 200);

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
