<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Plan;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class PlanDetail
 *
 * Livewire component responsible for displaying
 * detailed information of a single plan
 * in the Admin Settings section.
 */
class PlanDetail extends Component
{
    /**
     * Plan identifier used to fetch plan details.
     *
     * @var int|string
     */
    public $planDetailId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Assigns the plan identifier received from the route
     * to the component property.
     *
     * @param  int|string  $id  Plan ID
     * @return void
     */
    public function mount($id)
    {
        $this->planDetailId = $id;
    }

    /**
     * Render the Livewire component view.
     *
     * Fetches plan details based on the provided ID
     * and passes the data to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        $planDetail = Plan::where('id', $this->planDetailId)->first();

        return view('livewire.admin.setting.plan-detail', [
            'planDetail' => $planDetail,
        ]);
    }
}
