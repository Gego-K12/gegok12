<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Events;
use App\Models\Plugin;
use App\Models\School;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the 'event' profile_tab_scope added for the Admin -> Events ->
 * single event detail page (/admin/events/show/details/{id}) — same
 * "Additional Info" panel reused as-is, appended after the page's native
 * Vue <event-tab> component.
 */
class EventDetailPageHookTest extends TestCase
{
    use RefreshDatabase;

    private string $viewDir;

    protected function tearDown(): void
    {
        if (isset($this->viewDir) && is_dir($this->viewDir)) {
            array_map('unlink', glob($this->viewDir.'/*'));
            rmdir($this->viewDir);
            @rmdir(dirname($this->viewDir));
        }

        parent::tearDown();
    }

    private function publishProfileTabView(string $slug, string $markup): void
    {
        $this->viewDir = resource_path('views/plugins/'.$slug);
        mkdir($this->viewDir, 0755, true);
        file_put_contents($this->viewDir.'/profile-tab.blade.php', $markup);
    }

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    /**
     * MustBePrivilege middleware redirects every /admin/* route to
     * /admin/standard/create until the school has at least one Standard.
     */
    private function satisfyAdminOnboarding(School $school): void
    {
        Standard::create([
            'school_id' => $school->id,
            'name' => 'Grade 1',
            'slug' => 'grade-1-'.uniqid(),
            'status' => 1,
        ]);
    }

    private function createEvent(School $school, AcademicYear $year): Events
    {
        $event = new Events([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'select_type' => 'school',
            'title' => 'Staff Meeting',
            'category' => 'meeting',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek()->addHour(),
            'status' => 'active',
        ]);
        $event->batch = 'default';
        $event->color = 'green';
        $event->save();

        return $event;
    }

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'eventhook',
            'name' => 'Event Hook Plugin',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/repo.git',
            'composer_package' => 'gegok12/event-hook',
            'provider_class' => 'Gegok12\\EventHook\\EventHookServiceProvider',
            'portal' => 'admin',
            'status' => 'installed',
            'has_profile_tab' => true,
            'profile_tab_scope' => 'event',
            'profile_tab_label' => 'Event Hook Row',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    public function test_additional_info_panel_renders_on_the_event_detail_page_with_the_right_entity_id()
    {
        $this->publishProfileTabView('eventhook', '<span>event-entity-{{ $entityId }}</span>');
        $this->makePlugin();

        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $event = $this->createEvent($school, $this->activeAcademicYear($school));

        $response = $this->actingAs($admin)->get('/admin/events/show/details/'.$event->id);

        $response->assertOk();
        $response->assertSee('Additional Info');
        $response->assertSee('Event Hook Row');
        $response->assertSee('event-entity-'.$event->id);
    }

    public function test_a_class_scoped_plugin_does_not_appear_on_the_event_page()
    {
        $this->publishProfileTabView('eventhook', '<span>should-not-render</span>');
        $this->makePlugin(['profile_tab_scope' => 'class', 'profile_tab_label' => 'Class Only Row']);

        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $event = $this->createEvent($school, $this->activeAcademicYear($school));

        $response = $this->actingAs($admin)->get('/admin/events/show/details/'.$event->id);

        $response->assertOk();
        $response->assertDontSee('Class Only Row');
    }

    public function test_event_scope_does_not_leak_into_other_scopes()
    {
        $this->makePlugin(['slug' => 'eventonly', 'profile_tab_scope' => 'event']);

        $this->assertSame(0, Plugin::withProfileTabFor('teacher')->count());
        $this->assertSame(0, Plugin::withProfileTabFor('staff')->count());
        $this->assertSame(0, Plugin::withProfileTabFor('student')->count());
        $this->assertSame(0, Plugin::withProfileTabFor('class')->count());
        $this->assertSame(0, Plugin::withProfileTabFor('both')->count());
        $this->assertSame(1, Plugin::withProfileTabFor('event')->count());
    }
}
