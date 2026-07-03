<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

/**
 * Class CountryForm
 *
 * Livewire component responsible for creating
 * and updating country records in the Admin
 * Settings section.
 *
 * This component handles:
 * - Country creation
 * - Country editing
 * - Form validation
 * - Success alerts
 */
class CountryForm extends Component
{
    use LivewireAlert;

    /**
     * Country name.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $name;

    /**
     * Country short name.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $short_name;

    /**
     * Country ISO code.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $iso_code;

    /**
     * Country telephone prefix.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $tel_prefix;

    /**
     * Country status (active/inactive).
     *
     * @var int|string
     */
    #[Rule('required')]
    public $status = 1;

    /**
     * Country ID used for edit mode.
     *
     * @var int|string|null
     */
    public $countryEditId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Loads country details for edit mode and
     * populates form fields accordingly.
     *
     * @param  int|string|null  $id  Country ID
     * @return void
     */
    public function mount($id)
    {
        $this->countryEditId = $id;

        $countryEdit = Country::where('id', $this->countryEditId)->first();
        $this->name = $countryEdit->name;
        $this->short_name = $countryEdit->short_name;
        $this->iso_code = $countryEdit->iso_code;
        $this->tel_prefix = $countryEdit->tel_prefix;
        $this->status = $countryEdit->status;
    }

    /**
     * Handle country form submission.
     *
     * Validates input data and performs:
     * - Country update (if editing)
     * - Country creation (if new)
     *
     * Displays success alerts and redirects
     * back to the countries listing page.
     *
     * @return RedirectResponse
     */
    public function submitCountry()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'short_name' => $this->short_name,
            'iso_code' => $this->iso_code,
            'tel_prefix' => $this->tel_prefix,
            'status' => $this->status,
        ];

        if ($this->countryEditId != null) {
            Country::where('id', $this->countryEditId)->update($data);
            $this->alert('success', 'Country updated successfully');
        } else {
            Country::create($data);
            $this->alert('success', 'Country created successfully');
        }

        return redirect(url('/admin/setting/countries'));
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the country create/edit form.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.setting.country-form');
    }
}
