<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Exports;

use App\Models\Admission;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Exports the admin admission list, honoring the same filters applied on screen.
 */
class AdmissionExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(protected int $schoolId, protected int $academicYearId, protected array $filters)
    {
    }

    public function collection(): Collection
    {
        $query = Admission::with('standard')
            ->where('school_id', $this->schoolId)
            ->where('academic_year_id', $this->academicYearId);

        if (! empty($this->filters['application_status'])) {
            $query->where('application_status', $this->filters['application_status']);
        }

        if (! empty($this->filters['from_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if (! empty($this->filters['to_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }

        if (! empty($this->filters['status'])) {
            $query->where('application_payment_status', $this->filters['status']);
        }

        if (! empty($this->filters['mode'])) {
            $query->where('payment_mode', $this->filters['mode']);
        }

        if (! empty($this->filters['standard_id'])) {
            $query->where('standard_id', $this->filters['standard_id']);
        }

        return $query->orderByDesc('id')->get();
    }

    public function headings(): array
    {
        return [
            'Application Number',
            'Name',
            'Class Applied For',
            'Application Date',
            'Application Status',
            'Payment Mode',
            'Payment Status',
            'Amount Paid',
        ];
    }

    public function map($admission): array
    {
        return [
            $admission->application_no,
            $admission->name,
            strtoupper(optional($admission->standard)->name ?? ''),
            $admission->created_at?->format('d M Y'),
            $admission->application_status,
            match ($admission->payment_mode) {
                'razorpay' => 'Razorpay',
                'application_code' => 'Application Code',
                default => '-',
            },
            ucfirst($admission->application_payment_status ?: 'pending'),
            $admission->amount_paid,
        ];
    }
}
