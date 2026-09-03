<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Events;

use App\Helpers\CustomFieldHelper;
use App\Helpers\SiteHelper;
use App\Models\Events;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Create/Edit Event form -- ports resources/assets/js/components/event/
 * Create.vue and Edit.vue. Embedded in admin/events/index.blade.php (create
 * mode, opened via the "Create Event" menu) and admin/events/show.blade.php
 * (edit mode, opened via the "Edit" button on an event's own detail page --
 * that page dispatches the 'openEditEvent' browser event this component
 * listens for below, replacing Edit.vue's old hidden-button/jQuery hook).
 */
class EventForm extends Component
{
    use Common, LogActivity;

    public const CATEGORIES = [
        'education' => 'Education',
        'meeting' => 'Meeting',
        'culturals' => 'Culturals',
    ];

    public const FREQ_TERMS = [
        'day' => 'Day',
        'week' => 'Week',
        'month' => 'Month',
        'year' => 'Year',
    ];

    public ?int $eventId = null;

    public int $schoolId;

    public bool $showModal = false;

    public string $select_type = '';

    public string $title = '';

    public string $description = '';

    public string $repeats = '0';

    public string $standard_id = '';

    public string $freq = '';

    public string $freq_term = '';

    public string $location = '';

    public string $category = '';

    public string $organised_by = '';

    public string $batch = '';

    public string $start_date = '';

    public string $end_date = '';

    public array $standardlist = [];

    public $customFields;

    public array $custom_fields = [];

    public function mount(?int $eventId = null)
    {
        $this->schoolId = Auth::user()->school_id;
        $this->standardlist = json_decode(json_encode(SiteHelper::getStandardLinkList($this->schoolId)), true) ?? [];
        $this->customFields = CustomFieldHelper::getFieldsForEntity('event', $this->schoolId);

        foreach ($this->customFields as $field) {
            if ($field->field_type === 'checkbox') {
                $this->custom_fields[$field->id] = [];
            }
        }

        if ($eventId) {
            $event = Events::where('id', $eventId)->where('school_id', $this->schoolId)->firstOrFail();

            abort_unless(Gate::allows('event', $event), 403);

            $this->eventId = $eventId;
            $this->select_type = $event->select_type ?? '';
            $this->title = $event->title;
            $this->description = $event->description ?? '';
            $this->repeats = (string) $event->repeats;
            $this->standard_id = (string) ($event->standard_id ?? '');
            $this->freq = (string) ($event->freq ?? '');
            $this->freq_term = $event->freq_term ?? '';
            $this->location = $event->location ?? '';
            $this->category = $event->category ?? '';
            $this->organised_by = $event->organised_by ?? '';
            $this->batch = $event->batch ?? '';
            $this->start_date = $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '';
            $this->end_date = $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '';

            $storedValues = CustomFieldHelper::getValues('event', $eventId);

            foreach ($this->customFields as $field) {
                $stored = $storedValues->get($field->id);
                $value = $stored?->value;

                $this->custom_fields[$field->id] = ($field->field_type === 'checkbox' && $value)
                    ? explode(',', $value)
                    : ($field->field_type === 'checkbox' ? [] : $value);
            }
        }
    }

    public function isEdit(): bool
    {
        return $this->eventId !== null;
    }

    #[On('openEditEvent')]
    public function openEdit(): void
    {
        $this->resetErrorBag();
        $this->showModal = true;
    }

    #[On('openCreateEvent')]
    public function openCreate(string $selectType): void
    {
        $this->reset(['title', 'description', 'standard_id', 'freq', 'freq_term', 'location', 'category', 'organised_by', 'batch', 'start_date', 'end_date']);
        $this->repeats = '0';
        $this->select_type = $selectType;
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    protected function rules(): array
    {
        $rules = [
            'title' => ['required', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'description' => ['required', 'max:100'],
            'repeats' => ['required'],
            'location' => ['required', 'regex:/^[A-Za-z0-9_~\-!@#$%^&*.,:(\)\s]+$/'],
            'category' => ['required'],
            'organised_by' => ['required'],
            'start_date' => [
                'required',
                'date',
                'before_or_equal:end_date',
                function ($attribute, $value, $fail) {
                    if (date('Y-m-d', strtotime($value)) <= date('Y-m-d', strtotime('-1 days'))) {
                        $fail('Start Date Should Be After Yesterday');
                    }
                },
            ],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];

        if ($this->select_type === 'class') {
            $rules['standard_id'] = ['required'];
        }

        if ($this->select_type === 'alumni') {
            $rules['batch'] = ['required'];
        }

        if ($this->repeats === '1') {
            $rules['freq'] = ['required', 'not_in:0'];
            $rules['freq_term'] = ['required', 'not_in:0'];
        }

        return array_merge($rules, CustomFieldHelper::validationRules('event', $this->schoolId));
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Title Is Required',
            'title.regex' => 'Enter Only Alphabets',
            'description.required' => 'Description Is Required',
            'repeats.required' => 'Select Repeats',
            'standard_id.required' => 'Class Is Required',
            'freq.required' => 'Freq Is Required',
            'freq.not_in' => 'Freq Is Required',
            'freq_term.required' => 'Freq Term Is Required',
            'freq_term.not_in' => 'Freq Term Is Required',
            'location.required' => 'Location Is Required',
            'location.regex' => 'Enter A Valid Location',
            'category.required' => 'Event Category Is Required',
            'organised_by.required' => 'Organised By Is Required',
            'start_date.required' => 'Start Date Is Required',
            'start_date.before_or_equal' => 'Start Date Must Be Before End Date',
            'end_date.required' => 'End Date Is Required',
            'end_date.after_or_equal' => 'End Date Should Be After Start Date',
            'batch.required' => 'Batch Is Required',
        ];
    }

    protected function validationAttributes(): array
    {
        return CustomFieldHelper::validationAttributes('event', $this->schoolId);
    }

    public function save(): void
    {
        $this->validate();

        $event = $this->isEdit()
            ? Events::where('id', $this->eventId)->where('school_id', $this->schoolId)->firstOrFail()
            : new Events;

        if (! $this->isEdit()) {
            $event->school_id = $this->schoolId;
            $event->academic_year_id = SiteHelper::getAcademicYear($this->schoolId)->id;
        }

        $event->select_type = $this->select_type;
        $event->title = $this->title;
        $event->description = $this->description;
        $event->repeats = $this->repeats;
        $event->standard_id = $this->select_type === 'class' ? $this->standard_id : null;
        $event->batch = $this->select_type === 'alumni' ? $this->batch : '';
        $event->freq = $this->repeats === '1' ? $this->freq : 0;
        $event->freq_term = $this->repeats === '1' ? $this->freq_term : null;
        $event->location = $this->location;
        $event->category = $this->category;
        $event->organised_by = $this->organised_by;
        $event->start_date = date('Y-m-d H:i:s', strtotime($this->start_date));
        $event->end_date = date('Y-m-d H:i:s', strtotime($this->end_date));
        $event->color = $this->select_type === 'class' ? 'blue' : 'green';

        $event->save();

        foreach ($this->customFields as $field) {
            $value = $this->custom_fields[$field->id] ?? null;

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $value = is_array($value) ? implode(',', $value) : $value;

            CustomFieldHelper::setValue($field->id, 'event', $event->id, $this->schoolId, $value);
        }

        $message = trans($this->isEdit() ? 'messages.update_success_msg' : 'messages.add_success_msg', ['module' => 'Event']);

        $this->doActivityLog(
            $event,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            $this->isEdit() ? LOGNAME_EDIT_EVENT : LOGNAME_ADD_EVENT,
            $message
        );

        session()->put('successmessage', $message);

        $this->redirect(
            $this->isEdit() ? url('/admin/events/show/details/'.$event->id) : url('/admin/events'),
            navigate: false
        );
    }

    public function render()
    {
        return view('livewire.admin.events.event-form');
    }
}
