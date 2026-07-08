<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingSeoDetailRequest;
use App\Traits\Common;
use App\Traits\SettingProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class SeoDetailController
 *
 * Controller for managing SEO details: site title, descriptions,
 * social card images and related metadata.
 */
class SeoDetailController extends Controller
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
        //
        return view('admin.settings.seodetailsettings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SettingSeoDetailRequest $request)
    {
        //

        try {
            $this->updatesettings('sitetitle', $request->sitetitle);
            $this->updatesettings('site_description', $request->site_description);
            $this->updatesettings('site_keyword', $request->site_keyword);
            $this->updatesettings('twitter_handle', $request->twitter_handle);
            $this->updatesettings('twitter_description', $request->twitter_description);
            if (($request->twitter_card_image) == null) {
                $this->updatesettings('twitter_card_image', (\config::get('settings.twitter_card_image')));
            } else {
                $twittercardpath = $this->uploadFile('uploads/settings', $request->twitter_card_image);
                $this->updatesettings('twitter_card_image', $twittercardpath);
            }
            $this->updatesettings('facebook_site_url', $request->facebook_site_url);

            if (($request->facebook_card_image) == null) {
                $this->updatesettings('facebook_card_image', (\config::get('settings.facebook_card_image')));
            } else {
                $facebookcardpath = $this->uploadFile('uploads/settings', $request->facebook_card_image);
                $this->updatesettings('facebook_card_image', $facebookcardpath);
            }

            return redirect()->back();
        } catch (Exception $e) {
        }
    }
}
