<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Smstemplate;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class SmsTemplates
 *
 * Livewire component responsible for displaying
 * a paginated list of SMS templates in the
 * Admin Settings section.
 *
 * Features:
 * - Paginated SMS template listing
 * - Sorted by latest templates first
 */
class SmsTemplates extends Component
{
    use WithPagination;

    /**
     * Render the Livewire component view.
     *
     * Fetches paginated SMS templates ordered
     * by descending ID and passes the data
     * to the Blade view.
     *
     * @return View
     */
    public function render()
    {
        $sms_templates = Smstemplate::orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.setting.sms-templates', [
            'sms_templates' => $sms_templates,
        ]);
    }
}
