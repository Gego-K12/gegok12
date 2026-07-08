<?php

namespace App\Livewire\Admin\Setting;

use App\Models\City;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class CityDetail
 *
 * Livewire component responsible for displaying
 * detailed information of a single city
 * in the Admin Settings section.
 */
class CityDetail extends Component
{
    /**
     * City identifier used to fetch city details.
     *
     * @var int|string
     */
    public $cityDetailId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Assigns the city identifier received from the route
     * to the component property.
     *
     * @param  int|string  $id  City ID
     * @return void
     */
    public function mount($id)
    {
        $this->cityDetailId = $id;
    }

    /**
     * Render the Livewire component view.
     *
     * Fetches city details based on the provided city ID
     * and passes the data to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        $cityDetail = City::where('id', $this->cityDetailId)->first();

        return view('livewire.admin.setting.city-detail', [
            'cityDetail' => $cityDetail,
        ]);
    }
}
