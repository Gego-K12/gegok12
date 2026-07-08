<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Smstemplate;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Log;

/**
 * Class EditSmsTemplate
 *
 * Livewire component responsible for editing
 * existing SMS template records in the
 * Admin Settings section.
 *
 * This component handles:
 * - Loading SMS template details
 * - Form validation
 * - Updating SMS template data
 * - Success alerts
 */
class EditSmsTemplate extends Component
{
    use LivewireAlert;

    /**
     * SMS template identifier.
     *
     * @var int|string|null
     */
    public $sms_template_id;

    /**
     * External provider template ID.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $template_id;

    /**
     * SMS template status (active/inactive).
     *
     * @var bool|int|string
     */
    #[Rule('required')]
    public $status = true;

    /**
     * SMS template content/body.
     *
     * @var string|null
     */
    #[Rule('required')]
    public $template;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Loads SMS template details based on the provided ID
     * and populates the form fields.
     *
     * @param  int|string  $id  SMS template ID
     * @return void
     */
    public function mount($id)
    {
        $this->sms_template_id = $id;

        $sms_template = Smstemplate::where('id', $this->sms_template_id)->first();
        if ($sms_template) {
            $this->template_id = $sms_template->template_id;
            $this->template = $sms_template->template;
            $this->status = $sms_template->status;
        }
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the SMS template edit form.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.setting.edit-sms-template');
    }

    /**
     * Handle SMS template update submission.
     *
     * Validates input data, updates the SMS template,
     * displays a success alert, and redirects back
     * to the SMS template listing page.
     *
     * @return RedirectResponse|void
     */
    public function submitTemplate()
    {
        $this->validate();

        try {
            $data = [
                'template_id' => $this->template_id,
                'template' => $this->template,
                'status' => $this->status,
            ];

            Smstemplate::where('id', $this->sms_template_id)->update($data);

            $this->alert('success', 'SmsTemplate updated successfully');

            return redirect(url('/admin/setting/smstemplates'));

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
