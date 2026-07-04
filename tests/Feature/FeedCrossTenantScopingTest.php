<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\School;
use App\Models\Standard;
use App\Models\Tag;
use App\Models\User;
use App\Services\FeedReaderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Covers FeedReaderService, consolidated from 5 near-identical
 * FeedController copies (Admin, Accountant, Receptionist, Student,
 * Teacher). None of the 5 ever scoped their Post queries by school_id -
 * unlike the sibling PostsController::list(), which correctly scopes by
 * school_id + academic_year_id + is_posted for the same underlying data -
 * so any authenticated user in any school could see every other school's
 * classwall posts via /feeds. Also fixes filter()'s tag cloud joining a
 * table named `post_tag`, which doesn't exist (the real table is
 * `post_tags`), so filter() threw a SQL error on every call.
 */
class FeedCrossTenantScopingTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    private function satisfyAdminOnboarding(School $school): void
    {
        Standard::create([
            'school_id' => $school->id,
            'name' => 'Grade 1',
            'slug' => 'grade-1-'.uniqid(),
            'status' => 1,
        ]);
    }

    private function createPost(School $school, AcademicYear $year, array $overrides = []): Post
    {
        $user = User::factory()->schoolAdmin()->for($school)->create();

        return Post::forceCreate(array_merge([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'entity_id' => $user->id,
            'entity_name' => 'App\Models\User',
            'description' => 'A post',
            'visibility' => 'all_class',
            'attachment_file' => [],
            'is_posted' => 1,
            'created_by' => $user->id,
        ], $overrides));
    }

    public function test_reader_service_does_not_return_another_schools_posts(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $post = $this->createPost($school, $year);

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $this->createPost($otherSchool, $otherYear);

        $query = Post::query();
        app(FeedReaderService::class)->scopeToTenant($query, $school->id, $year->id);
        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertSame($post->id, $results->first()->id);
    }

    public function test_reader_service_excludes_unposted_drafts(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $this->createPost($school, $year, ['is_posted' => 0]);

        $query = Post::query();
        app(FeedReaderService::class)->scopeToTenant($query, $school->id, $year->id);

        $this->assertCount(0, $query->get());
    }

    public function test_admin_feeds_page_does_not_show_another_schools_posts(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $ownPost = $this->createPost($school, $year, ['description' => 'Own school post']);

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $this->createPost($otherSchool, $otherYear, ['description' => 'Other school post']);

        $response = $this->actingAs($admin)->get('/admin/feeds');

        $response->assertOk();
        $feeds = $response->viewData('feeds');
        $this->assertCount(1, $feeds);
        $this->assertSame($ownPost->id, $feeds->first()->id);
    }

    public function test_post_ids_matching_tag_are_scoped_to_the_school(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $post = $this->createPost($school, $year);

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $otherPost = $this->createPost($otherSchool, $otherYear);

        $tag = Tag::create(['tag_name' => 'birthday']);
        PostTag::create(['tag_id' => $tag->id, 'post_id' => $post->id]);
        PostTag::create(['tag_id' => $tag->id, 'post_id' => $otherPost->id]);

        $ids = app(FeedReaderService::class)->postIdsMatchingTag('birthday', $school->id);

        $this->assertSame([$post->id], $ids);
    }

    public function test_post_ids_matching_tag_uses_the_real_post_tags_table(): void
    {
        // The original bug: every filter() joined a table named
        // `post_tag` (singular), which does not exist - this would throw
        // a SQL error before ever reaching the assertions below.
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $post = $this->createPost($school, $year);

        $tag = Tag::create(['tag_name' => 'exam']);
        PostTag::create(['tag_id' => $tag->id, 'post_id' => $post->id]);

        $ids = app(FeedReaderService::class)->postIdsMatchingTag('exam', $school->id);

        $this->assertSame([$post->id], $ids);
    }

    public function test_tag_cloud_is_scoped_to_the_school(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $post = $this->createPost($school, $year);

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $otherPost = $this->createPost($otherSchool, $otherYear);

        $tag = Tag::create(['tag_name' => 'shared-tag-name']);
        PostTag::create(['tag_id' => $tag->id, 'post_id' => $post->id]);
        PostTag::create(['tag_id' => $tag->id, 'post_id' => $otherPost->id]);

        $cloud = app(FeedReaderService::class)->tagCloud($school->id);

        $this->assertCount(1, $cloud);
        $this->assertSame(1, (int) $cloud->first()->cnt);
    }

    public function test_accountant_feeds_page_respects_the_list_filter(): void
    {
        // Accountant's index() previously ignored $request->list entirely,
        // despite its own view linking to /accountant/feeds?list=X.
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $accountant = User::factory()->accountant()->for($school)->create();

        $this->createPost($school, $year, ['visibility' => 'all_class']);
        $selectClassPost = $this->createPost($school, $year, ['visibility' => 'select_class']);

        $response = $this->actingAs($accountant)->get('/accountant/feeds?list=select_class');

        $response->assertOk();
        $feeds = $response->viewData('feeds');
        $this->assertCount(1, $feeds);
        $this->assertSame($selectClassPost->id, $feeds->first()->id);
    }

    public function test_filter_endpoint_no_longer_throws_a_sql_error(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $this->createPost($school, $year);

        // Neither `list` nor `search` given - also exercises the
        // previously-undefined $feeds bug (filter() had no else branch).
        $this->actingAs($admin)->get('/admin/feed/filter')->assertOk();
    }

    public function test_filter_routes_exist_for_every_role_that_has_a_filter_view(): void
    {
        foreach (['accountant', 'student', 'teacher'] as $rolePrefix) {
            $route = collect(Route::getRoutes())
                ->first(fn ($r) => $r->uri() === "{$rolePrefix}/feed/filter");

            $this->assertNotNull($route, "Expected a {$rolePrefix}/feed/filter route to exist");
        }
    }
}
