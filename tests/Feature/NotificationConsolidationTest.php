<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Services\NotificationReaderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Covers NotificationReaderService::bellDropdownSummary(), consolidated
 * from 5 near-identical NotificationController::showList() copies. Two
 * bugs found and fixed during that consolidation:
 *
 * - Only Admin's copy actually included the notification's `type` in its
 *   response; Accountant/Receptionist/Student/Teacher all computed it but
 *   never assigned it into the output array.
 * - Accountant/Receptionist/Student called count($notification->data['data'])
 *   with no cast. When that payload is a plain string (not an array) -
 *   exactly the shape their own `else` branch treats as valid - count()
 *   throws a TypeError in PHP 8, which is NOT caught by `catch (Exception $e)`
 *   since TypeError extends Error, not Exception.
 */
class NotificationConsolidationTest extends TestCase
{
    use RefreshDatabase;

    private function insertNotification(User $user, array $data): void
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode($data),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_bell_dropdown_includes_the_notification_type(): void
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        $this->insertNotification($teacher, [
            'data' => ['data' => 'A new task was assigned', 'type' => 'task'],
        ]);

        $summary = app(NotificationReaderService::class)->bellDropdownSummary($teacher->fresh());

        $this->assertSame('A new task was assigned', $summary['list'][0]['data']);
        $this->assertSame('task', $summary['list'][0]['type']);
    }

    public function test_bell_dropdown_does_not_crash_on_a_plain_string_payload(): void
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        // A payload shape where data['data'] is a plain string, not an
        // array - exactly what the uncast count() call crashed on.
        $this->insertNotification($teacher, [
            'data' => 'Simple text notification',
        ]);

        $summary = app(NotificationReaderService::class)->bellDropdownSummary($teacher->fresh());

        $this->assertSame('Simple text notification', $summary['list'][0]['data']);
        $this->assertNull($summary['list'][0]['type']);
    }
}
