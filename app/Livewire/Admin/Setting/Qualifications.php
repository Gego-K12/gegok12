<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Qualification;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class Qualifications
 *
 * Livewire component responsible for managing
 * and displaying the list of qualifications in the
 * Admin Settings section.
 *
 * Features:
 * - Search qualifications by name
 * - Paginated qualification listing
 */
class Qualifications extends Component
{
    use WithPagination;

    /**
     * Search keyword used to filter qualifications by name.
     *
     * @var string
     */
    public $search = '';

    /**
     * Render the Livewire component view.
     *
     * Builds the qualification query with optional
     * search filtering and returns paginated results.
     *
     * @return View
     */
    public function render()
    {
        $qualifications = Qualification::query();

        if ($this->search) {
            $qualifications = $qualifications->where(function ($query) {
                $query->where('display_name', 'like', '%'.$this->search.'%');
            });
        }

        $qualifications = $qualifications->paginate(10);

        return view('livewire.admin.setting.qualifications', [
            'qualifications' => $qualifications,
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
