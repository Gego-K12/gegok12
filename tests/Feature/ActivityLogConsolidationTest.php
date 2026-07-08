<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\School;
use App\Models\User;
use App\Services\ActivityLogReaderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers ActivityLogReaderService, consolidated from 6 near-identical
 * ActivityLogController copies (Admin, Accountant, Librarian,
 * Receptionist, Student, Teacher). No bugs found - all 6 were already
 * correctly self-scoped by causer_id = Auth::id() - this just locks in
 * that scoping and the newest-first ordering as the service's contract.
 */
class ActivityLogConsolidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_returns_log_entries_caused_by_the_given_user(): void
    {
        $school = School::factory()->create();
        $teacherA = User::factory()->teacher()->for($school)->create();
        $teacherB = User::factory()->teacher()->for($school)->create();

        ActivityLog::create([
            'log_name' => 'test',
            'description' => 'Teacher A action',
            'causer_id' => $teacherA->id,
            'causer_type' => User::class,
            'properties' => [],
        ]);

        ActivityLog::create([
            'log_name' => 'test',
            'description' => 'Teacher B action',
            'causer_id' => $teacherB->id,
            'causer_type' => User::class,
            'properties' => [],
        ]);

        $result = app(ActivityLogReaderService::class)->forUser($teacherA->id);

        $this->assertCount(1, $result);
        $this->assertSame('Teacher A action', $result->first()->description);
    }

    public function test_orders_newest_first(): void
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        $older = ActivityLog::create([
            'log_name' => 'test',
            'description' => 'Older',
            'causer_id' => $teacher->id,
            'causer_type' => User::class,
            'properties' => [],
        ]);

        $newer = ActivityLog::create([
            'log_name' => 'test',
            'description' => 'Newer',
            'causer_id' => $teacher->id,
            'causer_type' => User::class,
            'properties' => [],
        ]);

        $result = app(ActivityLogReaderService::class)->forUser($teacher->id);

        $this->assertSame($newer->id, $result->first()->id);
        $this->assertSame($older->id, $result->last()->id);
    }
}
