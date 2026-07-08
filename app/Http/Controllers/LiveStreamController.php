<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class LiveStreamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the video player
     *
     * @return Renderable
     */
    public function viewPlayer()
    {

        $kv = \AWS::createClient('kinesisvideo');

        $args = [
            'StreamName' => 'NewTestStream',
            'APIName' => 'GET_DASH_STREAMING_SESSION_URL',
        ];

        $promise = $kv->getDataEndpointAsync($args);

        try {
            $result = $promise->wait();
        } catch (KinesisVideoException $e) {
            echo $e->getMessage();
        }

        $output = $result->toArray();
        $playpbackurl = $output['DataEndpoint'];

        $dashUrl = $this->getDashURL();

        return view('live-stream.media-player', [
            'playpbackurl' => $playpbackurl,
            'dashurl' => $dashUrl,
        ]);
    }

    public function getDashUrl()
    {

        $kvamc = \AWS::createClient('KinesisVideoArchivedMedia');

        $args = [
            'ClipFragmentSelector' => [
                'FragmentSelectorType' => 'SERVER_TIMESTAMP',
                'TimestampRange' => [
                    'EndTimestamp' => 1593943457,
                    'StartTimestamp' => 1593943357,
                ],
            ],
            'StreamName' => $streamName = 'NewTestStream',
            'APIName' => 'GET_DASH_STREAMING_SESSION_URL',
        ];

        $promise = $kvamc->getClipAsync($args);

        try {
            $output = $promise->wait();
        } catch (KinesisVideoArchivedMediaException $e) {
            $output = $e->getMessage();
        }

        \Storage::append('uploads\live-stream\$streamName.mp4', $output->get('Payload'));

        return $output;
    }
}
