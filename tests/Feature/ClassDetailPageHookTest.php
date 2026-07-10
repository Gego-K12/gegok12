<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\Plugin;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsNoticeBoardFixtures;
use Tests\TestCase;

/**
 * Covers the 'class' profile_tab_scope added for the Admin -> Classes ->
 * single class detail page (/admin/standardLink/show/{id}) — the
 * "Additional Info" panel (App\Livewire\Admin\ProfileExtraTabs) reused
 * as-is, the same way it already covers teacher/staff/student.
 */
class ClassDetailPageHookTest extends TestCase
{
    use RefreshDatabase;
    use BuildsNoticeBoardFixtures;

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

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'classhook',
            'name' => 'Class Hook Plugin',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/repo.git',
            'composer_package' => 'gegok12/class-hook',
            'provider_class' => 'Gegok12\\ClassHook\\ClassHookServiceProvider',
            'portal' => 'admin',
            'status' => 'installed',
            'has_profile_tab' => true,
            'profile_tab_scope' => 'class',
            'profile_tab_label' => 'Class Hook Row',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    public function test_additional_info_panel_renders_on_the_class_detail_page_with_the_right_entity_id()
    {
        $this->publishProfileTabView('classhook', '<span>class-entity-{{ $entityId }}</span>');
        $this->makePlugin();

        $school = School::factory()->create();
        $classTeacher = User::factory()->schoolAdmin()->for($school)->create();
        $year = $this->createActiveAcademicYear($school);
        $standardLink = $this->createStandardLink($school, $year, $classTeacher);

        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/standardLink/show/'.$standardLink->id);

        $response->assertOk();
        $response->assertSee('Additional Info');
        $response->assertSee('Class Hook Row');
        $response->assertSee('class-entity-'.$standardLink->id);
    }

    public function test_a_teacher_scoped_plugin_does_not_appear_on_the_class_page()
    {
        $this->publishProfileTabView('classhook', '<span>should-not-render</span>');
        $this->makePlugin(['profile_tab_scope' => 'teacher', 'profile_tab_label' => 'Teacher Only Row']);

        $school = School::factory()->create();
        $classTeacher = User::factory()->schoolAdmin()->for($school)->create();
        $year = $this->createActiveAcademicYear($school);
        $standardLink = $this->createStandardLink($school, $year, $classTeacher);

        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/standardLink/show/'.$standardLink->id);

        $response->assertOk();
        $response->assertDontSee('Teacher Only Row');
    }

    public function test_class_scope_does_not_leak_into_teacher_or_staff_pages()
    {
        $this->makePlugin(['slug' => 'classonly', 'profile_tab_scope' => 'class']);

        $this->assertSame(0, Plugin::withProfileTabFor('teacher')->count());
        $this->assertSame(0, Plugin::withProfileTabFor('staff')->count());
        $this->assertSame(0, Plugin::withProfileTabFor('both')->count());
        $this->assertSame(1, Plugin::withProfileTabFor('class')->count());
    }
}
