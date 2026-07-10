<?php

namespace App\Livewire\Admin\Addon;

use App\Traits\HandlesGuzzleRequests;
use Auth;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Log;

/**
 * Class Detail
 *
 * Livewire component responsible for displaying
 * add-on details and handling the purchase flow
 * in the Admin panel.
 *
 * This component:
 * - Fetches add-on details
 * - Loads available payment gateways
 * - Handles add-on purchase requests
 * - Redirects to payment gateways (Razorpay / Bank)
 */
class Detail extends Component
{
    use HandlesGuzzleRequests;

    /**
     * Add-on identifier.
     *
     * @var int|string|null
     */
    public $addon_id;

    /**
     * List of available payment gateways.
     *
     * @var array
     */
    public $paymentgateways = [];

    /**
     * Selected payment gateway ID.
     *
     * @var int|string|null
     */
    public $payment_gateway;

    /**
     * Currently authenticated user.
     *
     * @var Authenticatable|null
     */
    public $user;

    /**
     * Add-on purchase amount.
     *
     * @var float|int|null
     */
    public $amount;

    /**
     * Add-on detail response data.
     *
     * @var array|null
     */
    public $addon_detail;

    /**
     * Selected payment gateway detailed information.
     *
     * @var array|null
     */
    public $paymentgateway_detail;

    /**
     * Selected payment gateway name.
     *
     * @var string|null
     */
    public $gatewayname;

    /**
     * Indicates whether the purchase process is currently running.
     *
     * Used to prevent duplicate submissions.
     */
    public bool $isProcessing = false;

    /**
     * Add-on unique slug.
     *
     * @var string|null
     */
    public $addon_slug;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Initializes:
     * - Authenticated user
     * - Payment gateways
     * - Add-on details using slug
     *
     * @param  string  $slug  Add-on slug
     * @return void
     */
    public function mount($slug)
    {
        $this->addon_slug = $slug;
        $this->user = Auth::user();
        $this->paymentgateways = $this->getPaymentgateways();
        $this->addon_detail = $this->getAddonDetail();
    }

    /**
     * Render the Livewire component view.
     *
     * Passes add-on details, features, contact details,
     * and payment gateways to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.addon.detail', [
            'addondetail' => $this->addon_detail['data'],
            'contact_detail' => $this->addon_detail['contact'],
            'paymentgateways' => $this->paymentgateways,
            'addonfeatures' => $this->addon_detail['addon_features'],
        ]);
    }

    /**
     * Handle add-on purchase action.
     *
     * - Prevents duplicate requests
     * - Validates payment gateway selection
     * - Creates add-on purchase entry
     * - Initiates payment gateway flow
     *
     * @return RedirectResponse|void
     */
    public function buyAddon()
    {
        if ($this->isProcessing) {
            return;
        }
        $this->isProcessing = true;

        $validatedData = $this->validate([
            'payment_gateway' => 'required',
        ]);

        try {
            $gateway = collect($this->paymentgateways)->firstWhere('id', $this->payment_gateway);

            $status = 'pending';
            $create = [
                'addon_slug' => $this->addon_slug,
                'payment_gateway' => $this->payment_gateway,
                'domain_name' => request()->getHost(),
                'name' => $this->user->name,
                'email' => $this->user->email,
                'status' => $status,
                'amount' => $this->addon_detail['data']['price'],
                'mobile_no' => $this->user->mobile_no,
            ];

            $apiUrl = env('ADDON_API_URL').'/api/addon-purchase';

            $response = $this->guzzlePost($apiUrl, $create, true);

            if ($response && isset($response['success']) && $response['success'] &&
    $response['status'] !== 'exists') {
                $payment = $response['data'];
                $create['payment_id'] = $payment['id'];

                if ($gateway['gatewayname'] == 'razorpay') {
                    $paymentUrl = env('ADDON_API_URL').'/api/razorpay/payment';

                    $payment_response = $this->guzzlePost($paymentUrl, $create, true);

                    if ($payment_response && isset($payment_response['success']) && $payment_response['success']) {
                        $this->dispatch('redirect-to-url', $payment_response['link']);

                        return;
                    }
                }

                \Session::put('successmessage', $response['message']);
            }

            return redirect(url('admin/addon/'.$this->addon_slug.'/detail'));

        } catch (Exception $e) {
            $this->isProcessing = false;
            Log::info($e->getMessage());
        }

        $this->isProcessing = false;
    }

    /**
     * Handle payment gateway change event.
     *
     * Loads additional payment gateway details
     * when the selected gateway requires it
     * (e.g., bank transfer).
     *
     * @return void
     */
    public function paymentGatewayChange()
    {
        $gateway = collect($this->paymentgateways)->firstWhere('id', $this->payment_gateway);
        $this->gatewayname = $gateway['gatewayname'];

        if ($gateway['gatewayname'] == 'bank') {
            $apiUrl = env('ADDON_API_URL').'/api/paymentgateway/detail';

            $response = $this->guzzleGet($apiUrl, [
                'gatewayname' => $gateway['gatewayname'],
            ]);

            $this->paymentgateway_detail = $response['data'];
        }
    }

    /**
     * Fetch add-on details from external API.
     *
     * @return array|null
     */
    public function getAddonDetail()
    {
        $addon_url = env('ADDON_API_URL').'/api/detail/'.$this->addon_slug;

        try {
            $response = $this->guzzleGet($addon_url, [
                'email' => $this->user->email,
                'domain_name' => request()->getHost(),
                'addon_slug' => $this->addon_slug,
            ]);

            return $response;

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Fetch available payment gateways from API.
     *
     * @return array|null
     */
    public function getPaymentgateways()
    {
        $api_url = env('ADDON_API_URL').'/api/paymentgateways';

        try {
            $response = $this->guzzleGet($api_url);

            return $response['data'];

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
