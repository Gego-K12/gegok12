<?php

namespace App\Observers;

use App\Models\Bulletin;
use Illuminate\Support\Facades\Auth;

class BulletinObserver
{
    /**
     * Handle the bulletin "created" event.
     *
     * @return void
     */
    public function created(Bulletin $bulletin)
    {
        //
        /* $update=[
             'created_by'=>Auth::id(),
             'updated_by'=>Auth::id(),
         ];

         Bulletin::where('id',$bulletin->id)->update($update)*/
    }

    /**
     * Handle the bulletin "updated" event.
     *
     * @return void
     */
    public function updated(Bulletin $bulletin)
    {
        //
        $update = [
            'updated_by' => Auth::id(),
        ];

        Bulletin::where('id', $bulletin->id)->update($update);
    }

    /**
     * Handle the bulletin "deleted" event.
     *
     * @return void
     */
    public function deleted(Bulletin $bulletin)
    {
        //
    }

    /**
     * Handle the bulletin "restored" event.
     *
     * @return void
     */
    public function restored(Bulletin $bulletin)
    {
        //
    }

    /**
     * Handle the bulletin "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Bulletin $bulletin)
    {
        //
    }
}
