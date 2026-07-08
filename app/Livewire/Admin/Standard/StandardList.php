<?php

namespace App\Livewire\Admin\Standard;

use App\Models\Standard;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class StandardList extends Component
{
    use LivewireAlert;
    use WithPagination;

    public function render()
    {
        $standards = Standard::orderby('id', 'desc')->get();

        return view('livewire.admin.standard.standard-list', [
            'standards' => $standards,
        ]);
    }
}
