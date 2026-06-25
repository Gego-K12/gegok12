<?php

namespace App\Livewire\Admin\Standard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Standard;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class StandardList extends Component
{
    use WithPagination;
    use LivewireAlert;

    public function render()
    {
        $standards = Standard::orderby('id','desc')->paginate(15);
        return view('livewire.admin.standard.standard-list',[
            'standards' => $standards
        ]);
    }
}
