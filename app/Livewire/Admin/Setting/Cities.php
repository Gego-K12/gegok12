<?php

namespace App\Livewire\Admin\Setting;

use App\Models\City;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class Cities
 *
 * Livewire component responsible for managing
 * and displaying the list of cities in the
 * Admin Settings section.
 *
 * Features:
 * - Search cities by city name
 * - Search cities by related state name
 * - Paginated results
 */
class Cities extends Component
{
    use WithPagination;

    /**
     * Search keyword for filtering cities and states.
     *
     * @var string
     */
    public $search = '';

    /**
     * Render the Livewire component view.
     *
     * Builds the city query with optional search filters
     * and returns paginated results to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        $cities = City::with('state');

        if ($this->search) {
            $cities = $cities->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhereHas('state', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $cities = $cities->paginate(10);

        return view('livewire.admin.setting.cities', [
            'cities' => $cities,
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
