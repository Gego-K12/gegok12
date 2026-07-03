<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

/**
 * Class PlanForm
 *
 * Livewire component responsible for creating
 * and updating subscription plans in the
 * Admin Settings section.
 *
 * This component handles:
 * - Plan creation
 * - Plan editing
 * - Validation of plan limits
 * - Success alerts
 */
class PlanForm extends Component
{
    use LivewireAlert;

    /**
     * Billing cycle of the plan (monthly/yearly).
     *
     * @var string|null
     */
    #[Rule('required')]
    public $cycle;

    /**
     * Plan display name.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $name;

    /**
     * Display order of the plan.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $order;

    /**
     * Plan active status.
     *
     * @var int|string
     */
    #[Rule('required')]
    public $status = 1;

    /**
     * Plan price amount.
     *
     * @var float|int|string|null
     */
    #[Rule('required')]
    public $amount;

    /**
     * Maximum number of members allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_members;

    /**
     * Maximum number of events allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_events;

    /**
     * Maximum number of folders allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_folders;

    /**
     * Maximum number of files allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_files;

    /**
     * Maximum number of videos allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_videos;

    /**
     * Maximum number of audios allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_audios;

    /**
     * Maximum number of bulletins allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_bulletins;

    /**
     * Maximum number of groups allowed.
     *
     * @var int|string|null
     */
    #[Rule('required')]
    public $no_of_groups;

    /**
     * Plan ID used for edit mode.
     *
     * @var int|string|null
     */
    public $planEditId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Loads plan details when editing an existing plan
     * and populates the form fields.
     *
     * @param  int|string|null  $id  Plan ID
     * @return void
     */
    public function mount($id)
    {
        $this->planEditId = $id;

        if ($this->planEditId != '') {
            $planEdit = Plan::where('id', $this->planEditId)->first();
            $this->cycle = $planEdit->cycle;
            $this->name = $planEdit->name;
            $this->order = $planEdit->order;
            $this->status = $planEdit->is_active;
            $this->amount = $planEdit->amount;
            $this->no_of_members = $planEdit->no_of_members;
            $this->no_of_events = $planEdit->no_of_events;
            $this->no_of_folders = $planEdit->no_of_folders;
            $this->no_of_files = $planEdit->no_of_files;
            $this->no_of_videos = $planEdit->no_of_videos;
            $this->no_of_audios = $planEdit->no_of_audios;
            $this->no_of_bulletins = $planEdit->no_of_bulletins;
            $this->no_of_groups = $planEdit->no_of_groups;
        }
    }

    /**
     * Handle plan form submission.
     *
     * Validates input data and performs:
     * - Plan creation (if new)
     * - Plan update (if editing)
     *
     * Displays success alerts and redirects
     * to the plan detail page.
     *
     * @return RedirectResponse
     */
    public function submitPlan()
    {
        $this->validate();

        $data = [
            'cycle' => $this->cycle,
            'name' => $this->name,
            'order' => $this->order,
            'is_active' => $this->status,
            'amount' => $this->amount,
            'no_of_members' => $this->no_of_members,
            'no_of_events' => $this->no_of_events,
            'no_of_folders' => $this->no_of_folders,
            'no_of_files' => $this->no_of_files,
            'no_of_videos' => $this->no_of_videos,
            'no_of_audios' => $this->no_of_audios,
            'no_of_bulletins' => $this->no_of_bulletins,
            'no_of_groups' => $this->no_of_groups,
        ];

        if ($this->planEditId == '') {
            Plan::create($data);
            $this->alert('success', 'Plan added successfully');
        } else {
            Plan::where('id', $this->planEditId)->update($data);
            $this->alert('success', 'Plan updated successfully');
        }

        return redirect(url('/admin/setting/plan/detail/'.$this->planEditId));
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the plan create/edit form.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.setting.plan-form');
    }
}
