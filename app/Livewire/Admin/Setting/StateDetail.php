<?php

namespace App\Livewire\Admin\Setting;

use App\Models\State;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class StateDetail
 *
 * Livewire component responsible for displaying
 * detailed information of a single state
 * in the Admin Settings section.
 */
class StateDetail extends Component
{
    /**
     * State identifier used to fetch state details.
     *
     * @var int|string
     */
    public $StateDetailId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Assigns the state identifier received from the route
     * to the component property.
     *
     * @param  int|string  $id  State ID
     * @return void
     */
    public function mount($id)
    {
        $this->StateDetailId = $id;
    }

    /**
     * Render the Livewire component view.
     *
     * Fetches state details based on the provided ID
     * and passes the data to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        $stateDetail = State::where('id', $this->StateDetailId)->first();

        return view('livewire.admin.setting.state-detail', [
            'stateDetail' => $stateDetail,
        ]);
    }
}
