<?php
namespace App\Traits;
use App\Models\Setting;

trait SettingProcess
{
    /**
     * Update a setting value by key.
     *
     * @param string $key Setting key to update
     * @param mixed $value Value to persist
     * @return bool True on successful save
     */
     public function updatesettings($key,$value)
     {
     	
        $user=Setting::where('key',$key)->first();
        $user->value=$value;
        $user->save();

        return TRUE;
}
}
