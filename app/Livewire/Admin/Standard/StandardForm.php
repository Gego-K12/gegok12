<?php

namespace App\Livewire\Admin\Standard;

use App\Models\Standard;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

/**
 * Class StandardForm
 *
 * Livewire component for creating and updating a standard.
 */
class StandardForm extends Component
{
    use LivewireAlert;

    /**
     * The ID of the standard being edited, null when creating.
     *
     * @var int|null
     */
    public $standard_id;

    /**
     * The name of the standard.
     *
     * @var string
     */
    public $name;

    /**
     * The display order of the standard.
     *
     * @var int
     */
    public $order;

    /**
     * The active status of the standard.
     *
     * @var bool|int
     */
    public $status = 1;

    /**
     * Populate the form when editing an existing standard.
     *
     * @param  int|null  $id
     * @return void
     */
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

    /**
     * Render the standard form view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.standard.standard-form');
    }

    /**
     * Create a new standard or update the existing one being edited.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $this->validate([
            'name' => 'required|unique:standards,slug,'.$this->standard_id,

            'order' => 'required|numeric|min:1',

            'status' => 'required|boolean',
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

    /**
     * Reset the form fields back to their defaults.
     *
     * @return void
     */
    public function resetForm()
    {
        $this->reset([

            'name',

            'order',

            'status',

        ]);

        $this->status = 1;
    }
}
