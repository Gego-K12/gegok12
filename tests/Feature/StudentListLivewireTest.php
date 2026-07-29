<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Livewire\Admin\Student\StudentList;
use App\Models\School;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Covers the /admin/students Livewire rewrite: a real rows/columns table
 * with server-side pagination + eager loading (replacing the old Vue
 * card-grid, which fetched every matching student in one unpaginated,
 * un-eager-loaded request).
 */
class StudentListLivewireTest extends TestCase
{
    use RefreshDatabase;

    private School $school;

    private User $admin;

    private function makeStudent(School $school, string $firstname, string $lastname, array $overrides = []): User
    {
        $student = User::factory()->student()->for($school)->create($overrides);

        Userprofile::create([
            'user_id' => $student->id,
            'school_id' => $school->id,
            'usergroup_id' => $student->usergroup_id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'status' => 'active',
        ]);

        return $student;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::factory()->create();
        $this->admin = User::factory()->schoolAdmin()->for($this->school)->create();
    }

    public function test_renders_students_for_the_admins_school()
    {
        $this->makeStudent($this->school, 'Amelia', 'Rivers');
        $this->makeStudent($this->school, 'Brody', 'Stone');

        $otherSchool = School::factory()->create();
        $this->makeStudent($otherSchool, 'Outsider', 'Student');

        $this->actingAs($this->admin);

        Livewire::test(StudentList::class)
            ->assertSee('AMELIA RIVERS')
            ->assertSee('BRODY STONE')
            ->assertDontSee('OUTSIDER STUDENT');
    }

    public function test_search_filters_the_list()
    {
        $this->makeStudent($this->school, 'Amelia', 'Rivers');
        $this->makeStudent($this->school, 'Brody', 'Stone');

        $this->actingAs($this->admin);

        Livewire::test(StudentList::class)
            ->set('search', 'Amelia')
            ->assertSee('AMELIA RIVERS')
            ->assertDontSee('BRODY STONE');
    }

    public function test_letter_filter_narrows_the_list()
    {
        $this->makeStudent($this->school, 'Amelia', 'Rivers');
        $this->makeStudent($this->school, 'Brody', 'Stone');

        $this->actingAs($this->admin);

        Livewire::test(StudentList::class)
            ->set('letter', 'B')
            ->assertSee('BRODY STONE')
            ->assertDontSee('AMELIA RIVERS');
    }

    public function test_checkbox_selection_populates_selected_and_shows_bulk_actions()
    {
        $student = $this->makeStudent($this->school, 'Amelia', 'Rivers');

        $this->actingAs($this->admin);

        Livewire::test(StudentList::class)
            ->set('selected', [$student->id])
            ->assertSee('1 students selected')
            ->assertSee('Add Tag')
            ->assertSee('Send Message');
    }

    public function test_clear_filters_resets_search_letter_and_selection()
    {
        $student = $this->makeStudent($this->school, 'Amelia', 'Rivers');

        $this->actingAs($this->admin);

        Livewire::test(StudentList::class)
            ->set('search', 'Amelia')
            ->set('selected', [$student->id])
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('letter', '')
            ->assertSet('selected', []);
    }
}
