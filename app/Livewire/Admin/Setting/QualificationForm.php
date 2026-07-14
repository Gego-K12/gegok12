<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Qualification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

/**
 * Class QualificationForm
 *
 * Livewire component responsible for creating
 * and updating qualification records in the Admin
 * Settings section.
 *
 * This component handles:
 * - Qualification creation
 * - Qualification editing
 * - Form validation
 * - Success alerts
 */
class QualificationForm extends Component
{
    use LivewireAlert;

    /**
     * Qualification display name.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $display_name;

    /**
     * Qualification type.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $type;

    /**
     * Qualification status (active/inactive).
     *
     * @var int|string
     */
    #[Rule('required')]
    public $status = 1;

    /**
     * Qualification ID used for edit mode.
     *
     * @var int|string|null
     */
    public $qualificationEditId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Loads qualification details for edit mode and
     * populates form fields accordingly.
     *
     * @param  int|string|null  $id  Qualification ID
     * @return void
     */
    public function mount($id)
    {
        $this->qualificationEditId = $id;

        if ($this->qualificationEditId != '') {
            $qualificationEdit = Qualification::where('id', $this->qualificationEditId)->first();
            $this->display_name = $qualificationEdit->display_name;
            $this->type = $qualificationEdit->type;
            $this->status = $qualificationEdit->status;
        }
    }

    /**
     * Handle qualification form submission.
     *
     * Validates input data and performs:
     * - Qualification update (if editing)
     * - Qualification creation (if new)
     *
     * Displays success alerts and redirects
     * back to the qualifications listing page.
     *
     * @return RedirectResponse
     */
    public function submitQualification()
    {
        $this->validate();

        $data = [
            'display_name' => $this->display_name,
            'type' => $this->type,
            'status' => $this->status,
        ];

        if ($this->qualificationEditId != '') {
            Qualification::where('id', $this->qualificationEditId)->update($data);
            $this->alert('success', 'Qualification updated successfully');
        } else {
            Qualification::create($data);
            $this->alert('success', 'Qualification created successfully');
        }

        return redirect(url('/admin/setting/qualifications'));
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the qualification create/edit form.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.setting.qualification-form');
    }
}
