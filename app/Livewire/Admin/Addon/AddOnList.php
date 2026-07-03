<?php

namespace App\Livewire\Admin\Addon;

use App\Traits\HandlesGuzzleRequests;
use Exception;
use Illuminate\View\View;
use Livewire\Component;
use Log;

/**
 * Class AddOnList
 *
 * Livewire component responsible for displaying
 * the list of Add-ons in the Admin panel with pagination.
 *
 * Fetches add-on data from external API using Guzzle
 * and handles page navigation.
 */
class AddOnList extends Component
{
    use HandlesGuzzleRequests;

    /**
     * Stores API response containing add-on list data.
     *
     * @var array|null
     */
    public $addon;

    /**
     * Current pagination page number.
     *
     * @var int
     */
    public $currentPage = 1;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Fetches initial add-on data for the first page.
     *
     * @return void
     */
    public function mount()
    {
        $this->addon = $this->getAddons($this->currentPage);
    }

    /**
     * Render the Livewire component view.
     *
     * Passes add-on list and pagination data to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.addon.add-on-list', [
            'addonsList' => $this->addon['data'],
            'pagination' => [
                'meta' => $this->addon['meta'] ?? [],
                'links' => $this->addon['links'] ?? [],
            ],
        ]);
    }

    /**
     * Fetch add-ons list from the external API.
     *
     * @param  int  $page  Current page number
     * @return array|null API response data
     */
    public function getAddons($page)
    {
        $api_url = env('ADDON_API_URL').'/api/addons';

        try {
            $response = $this->guzzleGet($api_url, [
                'email' => auth()->user()->email,
                'domain_name' => request()->getHost(),
                'page' => $page,
            ]);

            return $response;

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Navigate to a specific pagination page.
     *
     * Ensures page number stays within valid limits
     * and refreshes the add-on list accordingly.
     *
     * @param  int  $page
     * @return void
     */
    public function goToPage($page)
    {
        $page = max(1, min($page, $this->addon['meta']['last_page'] ?? 1));
        $this->currentPage = $page;
        $this->addon = $this->getAddons($page);
    }
}
