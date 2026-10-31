<?php

namespace App\Support;

class AcademyClock
{
    public static function normalize(\Illuminate\Http\Request $request, string $prefix): void
    {
        if (!$request->exists($prefix.'_hour')) { return; }
        $data = $request->validate([$prefix.'_hour' => 'nullable|integer|between:1,12',
            $prefix.'_minute' => 'required_with:'.$prefix.'_hour|nullable|integer|between:0,59',
            $prefix.'_period' => ['required_with:'.$prefix.'_hour', 'nullable', \Illuminate\Validation\Rule::in(['AM', 'PM'])]]);
        $hour = $data[$prefix.'_hour'] ?? null;
        $request->merge([$prefix => $hour === null || $hour === '' ? null : sprintf('%02d:%02d', ((int) $hour % 12) + (($data[$prefix.'_period'] ?? 'AM') === 'PM' ? 12 : 0), (int) $data[$prefix.'_minute'])]);
    }

    public static function time(?string $time): string
    {
        if (!$time) { return 'Not set'; }
        $clock = \DateTimeImmutable::createFromFormat('!H:i', substr($time, 0, 5));
        return $clock ? $clock->format('g:i A') : 'Not set';
    }
}
