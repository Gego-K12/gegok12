<?php

namespace App\Conference;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Twilio\Exceptions\RestException;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;

/**
 * Class ConferenceService
 *
 * Handles video conference operations using Twilio,
 * including room creation, access token generation,
 * recording, and media storage.
 */
class ConferenceService
{
    /**
     * Twilio Account SID.
     *
     * @var string
     */
    protected $sid;

    /**
     * Twilio Auth Token.
     *
     * @var string
     */
    protected $token;

    /**
     * Twilio API Key.
     *
     * @var string
     */
    protected $key;

    /**
     * Twilio API Secret.
     *
     * @var string
     */
    protected $secret;

    /**
     * ConferenceService constructor.
     *
     * Initializes Twilio credentials from configuration.
     */
    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');
    }

    /**
     * Create or fetch a Twilio video room.
     *
     * @param  Request|array  $request
     * @param  string  $slug
     * @return array
     */
    public function storeData($request, $slug)
    {
        $message = '';
        $roomId = 0;
        try {
            $client = new Client($this->sid, $this->token);
            $exists = $client->video->rooms->read(['uniqueName' => $slug]);
            if (empty($exists)) {
                $room = $client->video->rooms->create([
                    'uniqueName' => $slug,
                    'type' => 'group',
                    'recordParticipantsOnConnect' => true,
                ]);
                $roomId = $room->sid;
                $status = true;
            }

        } catch (Exception $e) {
            $status = true;
            $message = $e->getMessage();
        }

        return ['status' => $status, 'message' => $message, 'room_id' => $roomId];
    }

    /**
     * Generate an access token for a video room.
     *
     * @param  string  $roomName
     * @param  string  $identity
     * @return array
     */
    public function showDetails($roomName = '', $identity = '')
    {
        $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);

        $videoGrant = new VideoGrant;
        $videoGrant->setRoom($roomName);

        $token->addGrant($videoGrant);

        return ['accessToken' => $token->toJWT()];
    }

    /**
     * Close a conference and create a video composition.
     *
     * @param  mixed  $conference
     * @param  int|string  $id
     * @return array
     */
    public function closeConnection($conference, $id)
    {
        $message = '';
        $compositionId = 0;
        try {
            $client = new Client($this->sid, $this->token);
            $composition = $client->video->compositions->create($conference->room_id, [
                'audioSources' => '*',
                'videoLayout' => [
                    'grid' => [
                        'video_sources' => ['*'],
                    ],
                ],
                'statusCallback' => url('video-conference/call-back'),
                'statusCallbackMethod' => 'GET',
                'format' => 'mp4',
            ]);
            $compositionId = $composition->sid;
            $status = true;
        } catch (RestException $e) {
            $status = false;
            $message = $e->getMessage();
        }

        return ['status' => $status, 'message' => $message, 'compose_id' => $compositionId];
    }

    /**
     * Save the recorded video file locally.
     *
     * @param  string  $school_slug
     * @param  string  $cid
     * @return string|null
     */
    public function saveVideo($school_slug, $cid = '')
    {
        try {
            $client = new Client($this->sid, $this->token);
            $uri = 'https://video.twilio.com/v1/Compositions/'.$cid.'/Media?Ttl=3600';
            $response = $client->request('GET', $uri);
            $mediaLocation = $response->getContent()['redirect_to'];
            $file_path = $school_slug.'/uploads/live-stream/stream_'.$cid.'.mp4';
            $path = \Storage::put($file_path, fopen($mediaLocation, 'r'));

            return $file_path;

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Fetch participant duration details for a room.
     *
     * @param  string  $roomName
     * @return array
     */
    public function showDuration($roomName = '')
    {
        $client = new Client($this->sid, $this->token);
        $uri = 'https://video.twilio.com/v1/Rooms/'.$roomName.'/Participants';

        $response = $client->request('GET', $uri);
        $list = $response->getContent();

        return $list;
    }
}
