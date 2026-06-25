<?php

namespace App\Livewire\Admin\Standard;

use App\Models\Standard;
use Livewire\Component;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class StandardForm extends Component
{
    use LivewireAlert;

    public $standard_id;

    public $name;

    public $order;

    public $status = 1;

    public function mount($id = null)
    {
        if ($id) {

            $standard = Standard::findOrFail($id);

            $this->standard_id = $standard->id;

            $this->name = $standard->name;

            $this->order = $standard->order;

            $this->status = $standard->status;
        }
    }

    public function render()
    {
        return view('livewire.admin.standard.standard-form');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:standards,slug,' . $this->standard_id,

            'order' => 'required|numeric|min:1',

            'status' => 'required',
        ]);

        Standard::updateOrCreate(

            ['id' => $this->standard_id],

            [

                'school_id' => auth()->user()->school_id,

                'name' => $this->name,

                'slug' => Str::slug($this->name),

                'order' => $this->order,

                'status' => $this->status,

            ]

        );

        $this->alert('success', 'Standard updated successfully');

        return redirect()->route('admin.standards');
    }

    public function resetForm()
    {
        $this->reset([

            'name',

            'order',

            'status'

        ]);

        $this->status = 1;
    }
}

