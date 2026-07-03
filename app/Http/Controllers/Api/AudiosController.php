<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\ShowAudio as ShowAudioResource;
use App\Models\Audio;
use Illuminate\Support\Facades\Auth;

class AudiosController extends Controller
{
    public function showaudio()
    {

        $audio = Audio::where('school_id', Auth::user()->school_id)->get();
        $audio = ShowAudioResource::collection($audio);

        return $audio;

    }
}
