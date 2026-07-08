<?php

namespace App\Helpers;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

/**
 * Class EnvironmentPathGenerator
 *
 * Custom path generator for Spatie Media Library.
 *
 * This class customizes the storage path for media files
 * based on the authenticated user's school context.
 *
 * Responsibilities:
 * - Define base media storage path
 * - Generate paths for original media
 * - Generate paths for conversions
 * - Generate paths for responsive images
 */
class EnvironmentPathGenerator implements PathGenerator
{
    /**
     * Base path where media files will be stored.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new EnvironmentPathGenerator instance.
     *
     * Initializes the media storage path using
     * the authenticated user's school slug.
     *
     * @return void
     */
    public function __construct()
    {
        $this->path = app()->env;

        /*
        if(env('FILESYSTEM_DRIVER')=='s3')
        {
            $this->path=env('AWS_ENDPOINT');
        }
        */

        $this->path = \Auth::user()->school->slug.'/files/small/';
    }

    /**
     * Get the base path for the given media.
     */
    public function getPath(Media $media): string
    {
        return $this->path.$media->id.'/';
    }

    /**
     * Get the path for media conversions.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'conversions/';
    }

    /**
     * Get the path for responsive images.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'responsive/';
    }
}
