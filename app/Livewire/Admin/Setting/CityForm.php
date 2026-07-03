<?php

namespace App\Livewire\Admin\Setting;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

/**
 * Class CityForm
 *
 * Livewire component responsible for creating
 * and updating city records in the Admin Settings section.
 *
 * This component handles:
 * - City creation
 * - City editing
 * - Country & state selection
 * - Form validation
 * - Success alerts
 */
class CityForm extends Component
{
    use LivewireAlert;

    /**
     * Selected country ID.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $country;

    /**
     * Selected state ID.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $state;

    /**
     * City name.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $name;

    /**
     * City status (active/inactive).
     *
     * @var int|string
     */
    #[Rule('required')]
    public $status = 1;

    /**
     * City ID used for edit mode.
     *
     * @var int|string|null
     */
    public $cityEditId;

    /**
     * List of states filtered by selected country.
     *
     * @var Collection|array|null
     */
    public $statelist;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Loads city details for edit mode
     * and initializes state list if country is available.
     *
     * @param  int|string|null  $id  City ID
     * @return void
     */
    public function mount($id)
    {
        $this->cityEditId = $id;

        if ($this->cityEditId != '') {
            $city = City::where('id', $this->cityEditId)->first();
            $this->country = $city->country_id;
            $this->state = $city->state_id;
            $this->name = $city->name;
            $this->status = $city->status;
        }

        if ($city->country_id != '') {
            $this->changeState();
        }
    }

    /**
     * Update the state list based on selected country.
     *
     * @return void
     */
    public function changeState()
    {
        $this->statelist = State::where('country_id', $this->country)->get();
    }

    /**
     * Handle city form submission.
     *
     * Validates input data and performs:
     * - City creation (if new)
     * - City update (if editing)
     *
     * Displays success alerts and redirects
     * back to the cities listing page.
     *
     * @return RedirectResponse
     */
    public function submitCity()
    {
        $this->validate();

        $data = [
            'country_id' => $this->country,
            'state_id' => $this->state,
            'name' => $this->name,
            'status' => $this->status,
        ];

        if ($this->cityEditId == '') {
            City::create($data);
            $this->alert('success', 'City added successfully');
        } else {
            City::where('id', $this->cityEditId)->update($data);
            $this->alert('success', 'City updated successfully');
        }

        return redirect(url('/admin/setting/cities'));
    }

    /**
     * Render the Livewire component view.
     *
     * Loads country list and state list
     * for the city form.
     *
     * @return View
     */
    public function render()
    {
        $countries = Country::get();

        return view('livewire.admin.setting.city-form', [
            'countries' => $countries,
            'states' => $this->statelist,
        ]);
    }
}
