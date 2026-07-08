<?php

namespace App\Livewire\Admin\Addon;

use App\Traits\HandlesGuzzleRequests;
use Auth;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Log;

/**
 * Class PurchaseHistory
 *
 * Livewire component responsible for displaying
 * the add-on purchase history for the authenticated user.
 *
 * This component handles:
 * - Fetching paginated purchase history
 * - Displaying purchase details
 * - Uploading payment attachments
 * - Pagination navigation
 */
class PurchaseHistory extends Component
{
    use HandlesGuzzleRequests;
    use WithFileUploads;

    /**
     * Authenticated user instance.
     *
     * @var Authenticatable|null
     */
    public $user;

    /**
     * Purchase history response data.
     *
     * Contains list, pagination, and contact details.
     *
     * @var array|null
     */
    public $purchase_histories;

    /**
     * Controls visibility of attachment upload modal.
     *
     * @var bool
     */
    public $show_attachment = false;

    /**
     * Uploaded attachment image file.
     *
     * @var TemporaryUploadedFile|null
     */
    public $image;

    /**
     * Selected purchase history identifier.
     *
     * @var int|string|null
     */
    public $purchase_history_id;

    /**
     * Current pagination page number.
     *
     * @var int
     */
    public $currentPage = 1;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Initializes authenticated user and loads
     * the first page of purchase history.
     *
     * @return void
     */
    public function mount()
    {
        $this->user = Auth::user();
        $this->purchase_histories = $this->getPurchaseHistory($this->currentPage);
    }

    /**
     * Render the Livewire component view.
     *
     * Passes purchase history data and pagination
     * details to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.addon.purchase-history', [
            'purchase_history_lists' => $this->purchase_histories['data'],
            'contact_detail' => $this->purchase_histories['contact'],
            'pagination' => [
                'meta' => $this->purchase_histories['meta'] ?? [],
                'links' => $this->purchase_histories['links'] ?? [],
            ],
        ]);
    }

    /**
     * Fetch purchase history data from external API.
     *
     * @param  int  $page  Current pagination page
     * @return array|null
     */
    public function getPurchaseHistory($page)
    {
        $purchase_history_url = env('ADDON_API_URL').'/api/addon/purchases';

        try {
            $response = $this->guzzleGet($purchase_history_url, [
                'email' => $this->user->email,
                'domain_name' => request()->getHost(),
                'page' => $page,
            ]);

            return $response;

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Open attachment upload modal for a specific purchase.
     *
     * @param  int|string  $purchase_history_id
     * @return void
     */
    public function openUploadModal($purchase_history_id)
    {
        $this->purchase_history_id = $purchase_history_id;
        $this->show_attachment = true;
    }

    /**
     * Upload attachment image for a purchase history record.
     *
     * Validates the uploaded file and sends it
     * to the external API for storage.
     *
     * @return void
     */
    public function uploadImage()
    {
        $this->validate([
            'image' => 'required|max:2048',
        ]);

        try {
            if ($this->image) {
                $paymentUrl = env('ADDON_API_URL').'/api/update/purchase/attachment';

                $data = [
                    'payment_id' => $this->purchase_history_id,
                    'attachment_file' => $this->image,
                ];

                $response = $this->guzzleImagePost($paymentUrl, $data, true);

                if ($response->getStatusCode() === 200) {
                    $response = json_decode($response->getBody(), true);
                    \Session::put('successmessage', $response['message']);
                }
            }

            $this->show_attachment = false;
            $this->dispatch('#refresh');

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Close the attachment upload modal.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->show_attachment = false;
    }

    /**
     * Navigate to a specific pagination page.
     *
     * Ensures the page number stays within valid bounds
     * and refreshes the purchase history list.
     *
     * @param  int  $page
     * @return void
     */
    public function goToPage($page)
    {
        $page = max(1, min($page, $this->purchase_histories['meta']['last_page'] ?? 1));
        $this->currentPage = $page;
        $this->purchase_histories = $this->getPurchaseHistory($page);
    }
}
