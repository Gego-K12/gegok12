<?php

namespace App\Observers;

use App\Models\Homework;
use Illuminate\Support\Facades\Auth;

class HomeworkObserver
{
    /**
     * Handle the homework "created" event.
     *
     * @return void
     */
    public function created(Homework $homework)
    {
        //
        $update = [
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];

        Homework::where('id', $homework->id)->update($update);
    }

    /**
     * Handle the homework "updated" event.
     *
     * @return void
     */
    public function updated(Homework $homework)
    {
        //
        $update = [
            'updated_by' => Auth::id(),
        ];

        Homework::where('id', $homework->id)->update($update);
    }

    /**
     * Handle the homework "deleted" event.
     *
     * @return void
     */
    public function deleted(Homework $homework)
    {
        //
    }

    /**
     * Handle the homework "restored" event.
     *
     * @return void
     */
    public function restored(Homework $homework)
    {
        //
    }

    /**
     * Handle the homework "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Homework $homework)
    {
        //
    }
}
