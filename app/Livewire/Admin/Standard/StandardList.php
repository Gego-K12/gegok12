<?php

namespace App\Livewire\Admin\Standard;

use App\Models\Standard;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class StandardList
 *
 * Livewire component for listing standards.
 */
class StandardList extends Component
{
    use LivewireAlert;
    use WithPagination;

    /**
     * Render the list of standards.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $standards = Standard::orderby('id', 'desc')->get();

        return view('livewire.admin.standard.standard-list', [
            'standards' => $standards,
        ]);
    }
}
