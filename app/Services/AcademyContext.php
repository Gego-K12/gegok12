<?php

namespace App\Services;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

/** Keep legacy relationship IDs while removing school-year setup from daily work. */
class AcademyContext
{
    private array $periods = [];
    private array $prepared = [];

    public function period(int $academyId): AcademicYear
    {
        if (isset($this->periods[$academyId])) {
            return $this->periods[$academyId];
        }
        $period = AcademicYear::where('school_id', $academyId)->where('status', 1)->orderByDesc('id')->first();
        if (!$period) {
            $period = DB::transaction(function () use ($academyId) {
                $academy = DB::table('schools')->where('id', $academyId)->whereNull('deleted_at')->lockForUpdate()->first();
                abort_unless($academy, 404);
                $existing = AcademicYear::where('school_id', $academyId)->where('status', 1)->orderByDesc('id')->first();
                return $existing ?: AcademicYear::create([
                    'school_id' => $academyId, 'name' => 'English courses',
                    'description' => 'Continuous academy teaching context; no annual promotion required.',
                    'start_date' => now()->startOfYear(), 'end_date' => now()->endOfYear(), 'status' => 1,
                ]);
            });
        }
        if ($period->name === 'English courses'
            && str_starts_with((string) $period->description, 'Continuous academy teaching context;')
            && $period->end_date->isPast()) {
            $period->end_date = now()->endOfYear();
            $period->save();
        }
        return $this->periods[$academyId] = $period;
    }

    public function prepare(int $academyId): AcademicYear
    {
        $period = $this->period($academyId);
        if (isset($this->prepared[$academyId])) {
            return $period;
        }
        // Existing classes and records are never renamed or remapped implicitly.
        if (!DB::table('standards')->where('school_id', $academyId)->whereNull('deleted_at')->exists()) {
            DB::transaction(function () use ($academyId) {
                DB::table('schools')->where('id', $academyId)->lockForUpdate()->first();
                if (DB::table('standards')->where('school_id', $academyId)->whereNull('deleted_at')->exists()) {
                    return;
                }
                foreach (range(1, 6) as $level) {
                    DB::table('standards')->insert([
                        'school_id' => $academyId, 'name' => 'Level '.$level,
                        'slug' => 'academy-'.$academyId.'-level-'.$level,
                        'order' => $level, 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            });
        }
        $this->prepared[$academyId] = true;
        return $period;
    }
}
