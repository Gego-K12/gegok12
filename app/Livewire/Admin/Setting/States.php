<?php

namespace App\Livewire\Admin\Setting;

use App\Models\State;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class States
 *
 * Livewire component responsible for managing
 * and displaying the list of states in the
 * Admin Settings section.
 *
 * Features:
 * - Search states by state name
 * - Search states by related country name
 * - Paginated state listing
 */
class States extends Component
{
    use WithPagination;

    /**
     * Search keyword used to filter states
     * and related country names.
     *
     * @var string
     */
    public $search = '';

    /**
     * Render the Livewire component view.
     *
     * Builds the state query with optional
     * search filters and returns paginated results
     * to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        $states = State::with('country');

        if ($this->search) {
            $states = $states->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhereHas('country', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $states = $states->paginate(10);

        return view('livewire.admin.setting.states', [
            'states' => $states,
        ]);
    }

    /**
     * Livewire hook triggered when the search property is updated.
     *
     * Resets pagination to the first page whenever
     * the search keyword changes.
     *
     * @return void
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
