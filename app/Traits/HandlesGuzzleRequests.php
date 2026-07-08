<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Log;
use Psr\Http\Message\ResponseInterface;

trait HandlesGuzzleRequests
{
    /**
     * Perform a GET request using Guzzle and return decoded JSON.
     *
     * @param  string  $url  Endpoint URL
     * @param  array  $params  Query parameters
     * @return array|null Decoded response or null on failure
     */
    protected function guzzleGet($url, $params = [])
    {

        $client = new Client(['verify' => false]);

        try {

            $response = $client->get($url, ['query' => $params]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return null;
    }

    /**
     * Perform a POST request using Guzzle and return decoded JSON.
     *
     * @param  string  $url  Endpoint URL
     * @param  array  $data  Payload data
     * @param  bool  $asJson  When true sends JSON, otherwise form-params
     * @return array|null Decoded response or null on failure
     */
    protected function guzzlePost($url, $data = [], $asJson = false)
    {
        $client = new Client(
            ['verify' => false]
        );

        try {
            $options = $asJson ? ['json' => $data] : ['form_params' => $data];

            $response = $client->post($url, $options);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

        } catch (Exception $e) {
            Log::error('Guzzle POST error: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Perform a POST request that can send files using multipart/form-data.
     *
     * @param  string  $url  Endpoint URL
     * @param  array  $data  Form data or files
     * @param  bool  $hasFile  Whether the payload includes uploaded files
     * @return ResponseInterface
     */
    public function guzzleImagePost($url, $data = [], $hasFile = false)
    {
        $client = new Client(['verify' => false]);

        if ($hasFile) {
            $multipart = [];

            foreach ($data as $key => $value) {
                if ($value instanceof UploadedFile) {
                    $multipart[] = [
                        'name' => $key,
                        'contents' => fopen($value->getRealPath(), 'r'),
                        'filename' => $value->getClientOriginalName(),
                    ];
                } else {
                    $multipart[] = [
                        'name' => $key,
                        'contents' => $value,
                    ];
                }
            }

            $options = ['multipart' => $multipart];
        } else {
            $options = ['form_params' => $data];
        }

        try {
            return $client->post($url, $options);
        } catch (Exception $e) {
            logger()->error('Guzzle POST error: '.$e->getMessage());
            throw $e;
        }
    }
}
