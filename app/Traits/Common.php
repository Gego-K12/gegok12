<?php
/**
 * Trait for processing common
 */
namespace App\Traits;

use App\Models\User;
use Exception;
use Log;

/**
 *
 * @class trait
 * Trait for Common Processes
 */
trait Common
{
    /**
     * Get the public URL for a stored file.
     *
     * @param string $file Storage path
     * @return string Publicly accessible URL or empty string on failure
     */
    public function getFilePath($file)
    {
        $path = '';

        try
        {
            $path = \Storage::url($file);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
        return $path;
    }

    /**
     * Upload a file to storage and return its path.
     *
     * @param string $folder Target folder path
     * @param \Illuminate\Http\UploadedFile $file File to upload
     * @return string Storage path for the uploaded file
     */
    public function uploadFile($folder,$file)
    {
        $path = '';

        try
        {
            $path = \Storage::disk('uploads')->putFile($folder, $file,'public');
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

        return $path;
    }

    /**
     * Write raw file contents to storage.
     *
     * @param string $folder Target path (including filename)
     * @param string $file Raw file contents
     * @return string Storage path of the written file
     */
    public function fileUpload($folder,$file)
    {
        $path = '';

        try
        {
            $path = \Storage::put($folder, $file,'public');
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

        return $path;
    }

    /**
     * Retrieve the client IP address considering proxies.
     *
     * @return string IP address
     */
    public function getRequestIP()
    {
        $ip = request()->ip();
        try
        {
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
        return $ip;
    }

    /**
     * Resolve default event image path by category.
     *
     * @param string $category Event category slug
     * @param string $image Unused parameter kept for compatibility
     * @return string|null Public URL for the event image or null on failure
     */
    public function eventImagePath($category,$image)
    {
        $image = '';

        try
        {
            if($category=='exam')
            {
                $image = \Storage::url('uploads/events/exam.png');
            }
            elseif($category=='culturals')
            {
                $image = \Storage::url('uploads/events/culturals.jpg');
            }
            elseif($category=='meeting')
            {
                $image = \Storage::url('uploads/events/meeting.jpg');
            }
            elseif($category=='education')
            {
                $image = \Storage::url('uploads/events/education.jpg');
            }

            return $image;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }   
    }

    /**
     * Save plain contents to storage with public visibility.
     *
     * @param string $folder Destination path
     * @param string $contents File contents
     * @return string Storage path of the saved file
     */
    public function putContents($folder,$contents)
    {
        $path = '';

        try
        {
            $path = \Storage::put($folder, $contents,'public');
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

        return $path;
    }

    /**
     * Save a file with a specific filename to storage.
     *
     * @param string $folder Destination folder
     * @param \Illuminate\Http\UploadedFile $contents File instance
     * @param string $filename Target filename
     * @return string Storage path of the saved file
     */
    public function putContentsByFilename($folder,$contents,$filename)
    {
        $path = '';

        try
        {
            $path = \Storage::putFileAs($folder, $contents,$filename);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

        return $path;
    }
    
    /**
     * Convert a Roman numeral string to an integer.
     *
     * @param string $roman Roman numeral value
     * @return int|null Converted integer or null on failure
     */
    public function romanToInteger($roman)
    {
        try
        {
            $result = 0;
            $array = array
            (
                'M'   => 1000,
                'CM'  => 900,
                'D'   => 500,
                'CD'  => 400,
                'C'   => 100,
                'XC'  => 90,
                'L'   => 50,
                'XL'  => 40,
                'X'   => 10,
                'IX'  => 9,
                'V'   => 5,
                'IV'  => 4,
                'I'   => 1
            );
            foreach ($array as $key => $value) 
            {
                while (strpos($roman, $key) === 0) 
                {
                    $result += $value;
                    $roman = substr($roman, strlen($key));
                }
            }
           
            // The Integer should be built, return it
            return $result;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }


    /**
     * Get file contents for download optionally from a specific disk.
     *
     * @param string $disk Storage disk name
     * @param string $file File path
     * @return string File contents
     */
    public function getFilePathforDownload($disk='',$file)
    { 
        $path = '';
        try
        {
            if($disk!='')
            { 
                $path = \Storage::disk($disk)->get($file);
            }
            else
            {
                $path = \Storage::get($file);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

        return $path;
    }

    /**
     * Delete a file from the S3 disk.
     *
     * @param string $file File path on S3
     * @return bool True after attempting deletion
     */
    public function unlinkFilePath($file)
    { 
        try
        {

            \Storage::disk('s3')->delete($file);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

        return TRUE;
    }

    /**
     * Determine if a user ID belongs to an admin role (usergroup_id == 3).
     *
     * @param int|string $userid User identifier
     * @return bool True if user is admin, false otherwise
     */
    public static function is_admin($userid)
    {
        if ($userid == '')
        {
            return FALSE;
        }
        else
        {
            $user = User::where('id', $userid)->first(); 

            if($user->usergroup_id == 3)
            {
                return TRUE;
            }
            return FALSE;
        }
    }
}
