<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\ShowEvent as ShowEventResource;
use App\Http\Resources\API\ShowEventGallery as ShowEventGalleryResource;
use App\Models\EventGallery;
use App\Models\Events;
use Illuminate\Support\Facades\Auth;

class EventGalleryController extends Controller
{
    public function showimage($event_id)
    {
        $event = Events::where([['id', $event_id], ['school_id', Auth::user()->school_id]])->get();
        $event = ShowEventResource::collection($event);

        return $event;

        /* $eventgallery = EventGallery::where([['event_id',$event_id],['school_id',Auth::user()->school_id]])->paginate(10);

            $eventgallery = ShowEventGalleryResource::collection($eventgallery);
            return $eventgallery;*/
    }
}
