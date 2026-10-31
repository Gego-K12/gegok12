<?php

namespace Tests\Feature;

use App\Auth\AcademyUserProvider;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Middleware\AcademyBackend;
use App\Models\Group;
use App\Models\Setting;
use App\Services\AcademyContext;
use App\Services\AcademyGroups;
use App\Services\ApplicationSettings;
use App\Services\LearningAccess;
use Illuminate\Auth\GenericUser;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AcademyBackendTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $connection = env('ACADEMY_TEST_DATABASE', 'sqlite');
        if (!in_array($connection, ['sqlite', 'mysql'], true)) {
            throw new \RuntimeException('Unsupported test database.');
        }
        if ($connection === 'mysql' && (getenv('CI') !== 'true' || config('database.connections.mysql.database') !== 'academy_test')) {
            throw new \RuntimeException('MySQL tests require the isolated CI academy_test database.');
        }
        config(['database.default' => $connection, 'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array', 'session.driver' => 'array', 'gexam.enabled' => false,
            'academy_backend.enabled' => true, 'app.key' => 'base64:'.base64_encode(str_repeat('b', 32))]);
        DB::purge($connection);
        if ($connection === 'mysql') { Schema::dropAllTables(); }
        Cache::flush(); app()->forgetInstance(AcademyContext::class);
        Schema::create('schools', function (Blueprint $t) { $t->id(); $t->string('name'); $t->boolean('status')->default(1); $t->softDeletes(); });
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('usergroup_id'); $t->string('name');
            $t->string('mobile_no')->nullable(); $t->string('email')->nullable(); $t->string('status')->default('active'); $t->string('password')->nullable();
            $t->unsignedBigInteger('ref_id')->nullable(); $t->string('registration_number')->nullable(); $t->softDeletes(); $t->timestamps();
            $t->rememberToken();
        });
        Schema::create('userprofiles', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->unsignedBigInteger('user_id'); $t->integer('usergroup_id');
            $t->string('firstname')->nullable(); $t->string('lastname')->nullable(); $t->string('avatar')->nullable();
            $t->string('status')->default('active'); $t->date('date_of_birth')->nullable(); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('academic_years', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->string('name'); $t->text('description')->nullable();
            $t->dateTime('start_date'); $t->dateTime('end_date'); $t->boolean('status'); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('standards', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->string('name'); $t->string('slug')->unique();
            $t->integer('order')->nullable(); $t->boolean('status')->default(1); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('sections', function (Blueprint $t) { $t->id(); $t->unsignedBigInteger('school_id'); $t->string('name'); $t->boolean('status')->default(1); $t->softDeletes(); $t->timestamps(); });
        Schema::create('standards_link', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('class_teacher_id');
            $t->integer('standard_id'); $t->integer('section_id'); $t->boolean('status')->default(1); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('subjects', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('standard_id');
            $t->integer('section_id'); $t->string('name'); $t->string('type'); $t->boolean('status')->default(1); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('class_teacher_links', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('standardLink_id');
            $t->integer('subject_id'); $t->integer('teacher_id'); $t->boolean('status')->default(1); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('groups', function (Blueprint $t) { $t->id(); $t->integer('standardLink_id'); $t->string('group_name'); $t->string('type')->default('class'); $t->boolean('status')->default(1); $t->softDeletes(); $t->timestamps(); });
        Schema::create('group_members', function (Blueprint $t) { $t->id(); $t->integer('group_id'); $t->integer('member_id'); $t->string('member_type'); $t->softDeletes(); $t->timestamps(); });
        Schema::create('student_academics', function (Blueprint $t) { $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('user_id'); $t->integer('standardLink_id')->nullable(); $t->softDeletes(); $t->timestamps(); });
        Schema::create('student_parent_links', function (Blueprint $t) { $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('student_id'); $t->integer('parent_id'); $t->boolean('status')->default(1); $t->timestamps(); $t->softDeletes(); });
        (require database_path('migrations/2026_10_30_000002_create_academy_student_journeys.php'))->up();
        (require database_path('migrations/2026_10_30_000003_add_academy_group_lessons.php'))->up();
        Schema::create('settings', function (Blueprint $t) { $t->id(); $t->string('key'); $t->string('value'); $t->timestamps(); });
        Schema::create('homeworks', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('standardLink_id');
            $t->integer('subject_id'); $t->string('description'); $t->date('date'); $t->softDeletes();
        });
        Schema::create('homework_approvals', function (Blueprint $t) { $t->id(); $t->integer('homework_id'); $t->string('status'); $t->softDeletes(); });
        Schema::create('attendances', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('user_id');
            $t->integer('standardLink_id')->nullable(); $t->integer('recorded_by')->nullable(); $t->text('remarks')->nullable(); $t->timestamps(); $t->date('date'); $t->string('session'); $t->integer('status'); $t->softDeletes();
        });
        Schema::create('teacher_leave_applications', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('school_id'); $t->integer('academic_year_id'); $t->integer('user_id');
            $t->integer('standardLink_id')->nullable(); $t->dateTime('from_date'); $t->dateTime('to_date');
            $t->text('remarks')->nullable(); $t->string('session')->nullable(); $t->string('status');
            $t->integer('approved_by')->nullable(); $t->date('approved_on')->nullable(); $t->text('comments')->nullable(); $t->timestamps(); $t->softDeletes();
        });
        (require database_path('migrations/2026_10_30_000004_improve_academy_teacher_workspace.php'))->up();
        foreach ([10, 20, 30] as $id) { DB::table('schools')->insert(['id' => $id, 'name' => 'Academy '.$id]); }
        foreach ([[1,3,10], [2,5,10], [3,6,10], [4,7,10], [5,5,10], [6,6,20], [7,6,10]] as [$id,$role,$school]) {
            DB::table('users')->insert(['id' => $id, 'school_id' => $school, 'usergroup_id' => $role, 'name' => 'Account '.$id, 'email' => 'user'.$id.'@example.test']);
            DB::table('userprofiles')->insert(['school_id' => $school, 'user_id' => $id, 'usergroup_id' => $role, 'firstname' => 'Account', 'lastname' => (string) $id]);
        }
        foreach ([10,20] as $school) { DB::table('academic_years')->insert(['id' => $school, 'school_id' => $school, 'name' => 'Existing period', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'status' => 1]); }
        DB::table('standards')->insert(['id' => 1, 'school_id' => 10, 'name' => 'Existing level', 'slug' => 'existing-level', 'order' => 1]);
        DB::table('sections')->insert(['id' => 1, 'school_id' => 10, 'name' => 'Original group']);
        DB::table('standards_link')->insert(['id' => 1, 'school_id' => 10, 'academic_year_id' => 10, 'class_teacher_id' => 2, 'standard_id' => 1, 'section_id' => 1]);
        DB::table('groups')->insert(['id' => 1, 'standardLink_id' => 1, 'group_name' => 'Original group']);
        DB::table('student_academics')->insert(['id' => 1, 'school_id' => 10, 'academic_year_id' => 10, 'user_id' => 3, 'standardLink_id' => 1]);
        DB::table('group_members')->insert(['group_id' => 1, 'member_id' => 3, 'member_type' => 'student']);
        DB::table('student_parent_links')->insert(['school_id' => 10, 'student_id' => 3, 'parent_id' => 4]);
    }

    private function actor(int $id, int $role, int $school = 10): GenericUser
    {
        return new GenericUser(['id' => $id, 'usergroup_id' => $role, 'school_id' => $school]);
    }

    public function test_fresh_academy_gets_one_context_and_six_levels_without_school_setup(): void
    {
        $context = app(AcademyContext::class);
        $period = $context->prepare(30);
        $this->assertSame($period->id, $context->prepare(30)->id);
        $this->assertDatabaseCount('academic_years', 3);
        $this->assertSame(6, DB::table('standards')->where('school_id', 30)->count());
    }

    public function test_existing_records_and_current_period_are_not_renamed_or_selected_globally(): void
    {
        Cache::put('academic_year', 20);
        $this->assertSame(10, (int) \App\Helpers\SiteHelper::getAcademicYear(10)->id);
        app(AcademyContext::class)->prepare(10);
        $this->assertDatabaseHas('standards', ['id' => 1, 'name' => 'Existing level']);
        $this->assertSame(1, DB::table('standards')->where('school_id', 10)->count());
    }

    public function test_direct_group_creation_adds_two_units_and_teacher_links_to_existing_learning_tables(): void
    {
        $group = (new AcademyGroups)->create(10, ['group_name' => 'Tuesday English', 'level' => 2, 'teacher_id' => 2]);
        $link = DB::table('standards_link')->where('id', $group->standardLink_id)->first();
        $this->assertSame(2, (int) $link->class_teacher_id);
        $this->assertSame(['Unit 1', 'Unit 2'], DB::table('subjects')->where('section_id', $link->section_id)->orderBy('name')->pluck('name')->all());
        $this->assertSame(2, DB::table('class_teacher_links')->where('standardLink_id', $link->id)->count());
        $this->assertSame('Level 2', DB::table('standards')->where('id', $link->standard_id)->value('name'));
    }

    public function test_legacy_group_payload_and_json_fields_still_work(): void
    {
        (new AcademyGroups)->create(10, ['group_name' => 'Practice', 'standards_link_id' => 1]);
        Auth::setUser($this->actor(1, 3));
        $json = (new GroupController)->index(1)->getData(true);
        $this->assertTrue($json['success']);
        $this->assertCount(2, $json['data']);
        $this->assertSame(['id','group_name','created_at'], array_keys($json['data'][0]));
    }

    public function test_group_members_relationship_uses_group_id_not_member_primary_key(): void
    {
        DB::table('group_members')->insert(['id' => 99, 'group_id' => 1, 'member_id' => 7, 'member_type' => 'student']);
        $this->assertSame(2, Group::findOrFail(1)->members()->count());
    }

    public function test_enrollment_is_idempotent_and_updates_learning_context_preserving_old_rows(): void
    {
        $service = new AcademyGroups;
        $group = $service->create(10, ['group_name' => 'Next group', 'level' => 2, 'teacher_id' => 2]);
        $service->enroll(10, $group->id, [3]);
        $service->enroll(10, $group->id, [3]);
        $this->assertSame(1, DB::table('group_members')->where('group_id', $group->id)->where('member_id', 3)->whereNull('deleted_at')->count());
        $this->assertDatabaseHas('student_academics', ['user_id' => 3, 'standardLink_id' => $group->standardLink_id, 'deleted_at' => null]);
        $this->assertNotNull(DB::table('student_academics')->where('id', 1)->value('deleted_at'));
        $this->assertDatabaseCount('student_academics', 2);
    }

    public function test_foreign_student_rejection_rolls_back_the_entire_batch(): void
    {
        try {
            (new AcademyGroups)->enroll(10, 1, [7, 6]);
            $this->fail('Cross-academy enrollment must fail.');
        } catch (ValidationException $e) {
            $this->assertDatabaseCount('group_members', 1);
            $this->assertDatabaseCount('student_academics', 1);
        }
    }

    public function test_group_lists_are_scoped_to_the_current_academy(): void
    {
        Auth::setUser($this->actor(9, 3, 20));
        $this->assertSame([], (new GroupController)->list()->getData(true)['data']);
    }

    public function test_parents_see_only_linked_children_and_students_only_themselves(): void
    {
        $access = new LearningAccess;
        $this->assertSame([3], $access->students($this->actor(4, 7))->pluck('id')->all());
        $this->assertSame([3], $access->students($this->actor(3, 6))->pluck('id')->all());
        $this->expectException(HttpException::class);
        $access->student($this->actor(4, 7), 7);
    }

    public function test_teachers_read_only_their_assigned_students(): void
    {
        $access = new LearningAccess;
        $this->assertSame([3], $access->students($this->actor(2, 5))->pluck('id')->all());
        $this->assertSame([], $access->students($this->actor(5, 5))->pluck('id')->all());
        $this->expectException(HttpException::class);
        $access->student($this->actor(2, 5), 6);
    }

    public function test_parent_homework_api_returns_only_approved_work_for_the_linked_child(): void
    {
        foreach ([1,2] as $id) { DB::table('homeworks')->insert(['id' => $id, 'school_id' => 10, 'academic_year_id' => 10, 'standardLink_id' => 1, 'subject_id' => 1, 'description' => 'Work '.$id, 'date' => '2026-10-30']); }
        DB::table('homework_approvals')->insert(['homework_id' => 1, 'status' => 'approved']);
        DB::table('homework_approvals')->insert(['homework_id' => 2, 'status' => 'pending']);
        $this->actingAs($this->actor(4, 7))->getJson('/learning/students/3/homework')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', 1);
        $this->getJson('/learning/students/6/homework')->assertNotFound();
    }

    public function test_settings_are_cached_and_invalidated_on_update(): void
    {
        Setting::create(['key' => 'login_status', 'value' => '1']);
        $service = new ApplicationSettings;
        $this->assertSame('1', $service->values()['login_status']);
        DB::enableQueryLog(); DB::flushQueryLog();
        $service->values(); $service->values();
        $this->assertCount(0, DB::getQueryLog());
        Setting::first()->update(['value' => '0']);
        $this->assertSame('0', $service->values()['login_status']);
    }

    public function test_auth_provider_loads_profile_without_loading_family_relations(): void
    {
        DB::enableQueryLog(); DB::flushQueryLog();
        $user = (new AcademyUserProvider(app('hash'), \App\Models\User::class))->retrieveById(3);
        $this->assertTrue($user->relationLoaded('userprofile'));
        $this->assertFalse($user->relationLoaded('members'));
        $this->assertFalse($user->relationLoaded('children'));
        $this->assertFalse($user->relationLoaded('parents'));
        $this->assertLessThanOrEqual(2, count(DB::getQueryLog()));
    }

    public function test_student_list_serialization_has_a_bounded_query_count_and_preserves_fields(): void
    {
        foreach (range(100, 119) as $id) {
            DB::table('users')->insert(['id' => $id, 'school_id' => 10, 'usergroup_id' => 6, 'name' => 'Student '.$id]);
            DB::table('userprofiles')->insert(['school_id' => 10, 'user_id' => $id, 'usergroup_id' => 6, 'firstname' => 'Student', 'lastname' => (string) $id]);
            DB::table('student_academics')->insert(['school_id' => 10, 'academic_year_id' => 10, 'user_id' => $id, 'standardLink_id' => 1]);
            DB::table('student_parent_links')->insert(['school_id' => 10, 'student_id' => $id, 'parent_id' => 4]);
        }
        DB::enableQueryLog(); DB::flushQueryLog();
        $processor = new class { use \App\Traits\MemberProcess; };
        $request = Request::create('/admin/students/find', 'GET', ['status' => 'active']);
        $rows = $processor->MemberFilter($request, 10, 6, 'active')->resolve($request);
        $this->assertCount(22, $rows);
        $this->assertLessThanOrEqual(9, count(DB::getQueryLog()));
        $row = collect($rows)->firstWhere('id', 100);
        $this->assertSame([4], $row['parent_id']);
        $this->assertNotEmpty($row['class']);
        $this->assertArrayHasKey('librarycard_number', $row);
        $this->assertNull($row['librarycard_number']);
    }

    public function test_school_only_actions_are_blocked_but_homework_is_allowed(): void
    {
        $middleware = new AcademyBackend;
        $request = Request::create('/admin/promotion/create', 'POST');
        $request->headers->set('Accept', 'application/json'); $request->setUserResolver(fn () => $this->actor(1, 3));
        $this->assertSame(410, $middleware->handle($request, fn () => response('must not run'))->status());
        $request = Request::create('/admin/homeworks'); $request->setUserResolver(fn () => $this->actor(1, 3));
        $this->assertSame('homework allowed', $middleware->handle($request, fn () => response('homework allowed'))->getContent());
    }

    public function test_index_migration_is_idempotent_and_reversible_without_data_loss(): void
    {
        $migration = require database_path('migrations/2026_10_30_000001_add_academy_learning_indexes.php');
        $migration->up(); $migration->up();
        $this->assertTrue(Schema::hasIndex('users', 'academy_users_lookup'));
        $this->assertTrue(Schema::hasIndex('homeworks', 'academy_homework_lookup'));
        $migration->down();
        $this->assertFalse(Schema::hasIndex('users', 'academy_users_lookup'));
        $this->assertDatabaseCount('users', 7);
    }

    public function test_real_login_accepts_the_four_core_roles_and_keeps_the_existing_redirect(): void
    {
        \Illuminate\Support\Facades\Event::fake([\Illuminate\Auth\Events\Login::class]);
        config(['hashing.bcrypt.rounds' => 4]);
        foreach ([1, 2, 3, 4] as $id) {
            DB::table('users')->where('id', $id)->update(['password' => \Illuminate\Support\Facades\Hash::make('correct-password')]);
            $this->post('/login', ['email' => 'user'.$id.'@example.test', 'password' => 'correct-password'])
                ->assertRedirect('/learning/academy');
            $this->assertSame($id, (int) Auth::id());
            Auth::logout();
        }
    }

    public function test_login_handles_unknown_and_inactive_accounts_without_a_server_error(): void
    {
        $this->post('/login', ['email' => 'missing@example.test', 'password' => 'wrong'])->assertSessionHasErrors('email');
        DB::table('userprofiles')->where('user_id', 3)->update(['status' => 'inactive']);
        $this->post('/login', ['email' => 'user3@example.test', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_academy_login_does_not_queue_a_login_email_by_default(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $listener = \Mockery::mock(\App\Listeners\LogSuccessfulLogin::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $listener->shouldReceive('getRequestIP')->once()->andReturn('127.0.0.1');
        $listener->shouldReceive('doActivityLog')->once();
        $user = new GenericUser(['id' => 3, 'school_id' => 10, 'usergroup_id' => 6, 'email' => 'user3@example.test']);
        $listener->handle(new \Illuminate\Auth\Events\Login('web', $user, false));
        \Illuminate\Support\Facades\Mail::assertNothingQueued();
    }

    private function prepareJourney(int $level = 1, int $unit = 1): void
    {
        app(\App\Services\AcademyStudents::class)->placement($this->actor(1, 3), 3, [
            'placement_status' => 'completed', 'placement_date' => '2026-10-01', 'level' => $level, 'unit' => $unit,
        ]);
        DB::table('academy_group_settings')->insert(['school_id' => 10, 'group_id' => 1, 'level' => $level, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_registration_creates_original_login_profile_pending_placement_and_safe_audit(): void
    {
        $id = app(\App\Services\AcademyStudents::class)->register($this->actor(1, 3), [
            'firstname' => 'New', 'lastname' => 'Learner', 'email' => 'new@example.test', 'password' => 'private-initial-password',
            'date_of_birth' => now()->subYears(9)->toDateString(),
        ]);
        $this->assertDatabaseHas('users', ['id' => $id, 'school_id' => 10, 'usergroup_id' => 6, 'status' => 'active']);
        $this->assertDatabaseHas('userprofiles', ['user_id' => $id, 'firstname' => 'New']);
        $this->assertDatabaseHas('academy_student_journeys', ['student_id' => $id, 'placement_status' => 'pending', 'level' => null]);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('private-initial-password', DB::table('users')->where('id', $id)->value('password')));
        $this->assertStringNotContainsString('private-initial-password', DB::table('academy_learning_events')->value('details'));
    }

    public function test_teacher_cannot_register_accounts_or_change_placement(): void
    {
        $this->expectException(HttpException::class);
        app(\App\Services\AcademyStudents::class)->placement($this->actor(2, 5), 3, []);
    }

    public function test_group_assignment_requires_placement_and_matching_level_and_updates_original_enrollment(): void
    {
        $service = app(\App\Services\AcademyStudents::class);
        $group = app(AcademyGroups::class)->create(10, ['group_name' => 'New group', 'teacher_id' => 5, 'level' => 2]);
        DB::table('academy_group_settings')->insert(['school_id' => 10, 'group_id' => $group->id, 'level' => 2]);
        try { $service->assign($this->actor(1, 3), 7, $group->id); $this->fail('Placement is required.'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('group_id', $e->errors()); }
        $service->placement($this->actor(1, 3), 7, ['placement_status' => 'waived', 'level' => 1, 'unit' => 1]);
        try { $service->assign($this->actor(1, 3), 7, $group->id); $this->fail('Wrong level must fail.'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('group_id', $e->errors()); }
        $service->placement($this->actor(1, 3), 7, ['placement_status' => 'waived', 'level' => 2, 'unit' => 1]);
        $service->assign($this->actor(1, 3), 7, $group->id);
        $this->assertDatabaseHas('student_academics', ['user_id' => 7, 'standardLink_id' => $group->standardLink_id, 'deleted_at' => null]);
        $this->assertDatabaseHas('group_members', ['member_id' => 7, 'group_id' => $group->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('academy_learning_events', ['student_id' => 7, 'event' => 'group_assigned']);
    }

    public function test_teacher_class_queries_exclude_unassigned_classes_and_cache_does_not_leak_admin_list(): void
    {
        $other = app(AcademyGroups::class)->create(10, ['group_name' => 'Other teacher', 'level' => 2, 'teacher_id' => 5]);
        \App\Helpers\SiteHelper::getStandardLinkList(10);
        Auth::setUser($this->actor(2, 5));
        $this->assertSame([1], \App\Models\StandardLink::orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->all());
        $list = \App\Helpers\SiteHelper::getStandardLinkList(10);
        $this->assertCount(1, $list);
        $this->assertFalse(app(\App\Services\AcademyAssignments::class)->allows($this->actor(2, 5), $other->standardLink_id));
        $response = app(\App\Http\Controllers\Teacher\GroupController::class)->index(1)->getData(true);
        $this->assertCount(1, $response['data']);
    }

    public function test_eager_teacher_relationship_works_without_school_id_on_empty_relation_instance(): void
    {
        DB::table('subjects')->insert(['id' => 1, 'school_id' => 10, 'academic_year_id' => 10, 'standard_id' => 1, 'section_id' => 1, 'name' => 'Unit 1', 'type' => 'core']);
        DB::table('class_teacher_links')->insert(['school_id' => 10, 'academic_year_id' => 10, 'standardLink_id' => 1, 'teacher_id' => 2, 'subject_id' => 1]);
        $teacher = \App\Models\Users\TeacherUser::without(['members', 'children', 'parents'])
            ->with(['teacherlinkCurrentAcademicYear' => fn ($q) => $q->without('teacher')])->findOrFail(2);
        $this->assertCount(1, $teacher->teacherlinkCurrentAcademicYear);
    }

    public function test_feedback_advances_sequentially_and_repeated_stale_submit_cannot_skip_units(): void
    {
        $this->prepareJourney();
        $service = app(\App\Services\AcademyStudents::class);
        $data = ['feedback' => 'Great speaking progress.', 'advance' => 1, 'expected_level' => 1, 'expected_unit' => 1];
        $service->feedback($this->actor(2, 5), 3, $data);
        $this->assertDatabaseHas('academy_student_journeys', ['student_id' => 3, 'level' => 1, 'unit' => 2]);
        $this->assertDatabaseHas('academy_teacher_feedback', ['student_id' => 3, 'level' => 1, 'unit' => 1, 'advances' => 1]);
        try { $service->feedback($this->actor(2, 5), 3, $data); $this->fail('Stale submissions must fail.'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('feedback', $e->errors()); }
        $service->feedback($this->actor(2, 5), 3, array_replace($data, ['expected_unit' => 2]));
        $this->assertDatabaseHas('academy_student_journeys', ['student_id' => 3, 'level' => 2, 'unit' => 1]);
        try { $service->feedback($this->actor(2, 5), 3, array_replace($data, ['expected_level' => 2])); $this->fail('A new level needs a matching group.'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('feedback', $e->errors()); }
        $this->assertDatabaseCount('academy_teacher_feedback', 2);
    }

    public function test_last_unit_completes_course_without_repeating_or_creating_level_seven(): void
    {
        $this->prepareJourney(6, 2);
        app(\App\Services\AcademyStudents::class)->feedback($this->actor(2, 5), 3, [
            'feedback' => 'Course completed. Keep practising!', 'advance' => 1, 'expected_level' => 6, 'expected_unit' => 2,
        ]);
        $j = DB::table('academy_student_journeys')->where('student_id', 3)->first();
        $this->assertNotNull($j->completed_at); $this->assertSame(6, (int) $j->level); $this->assertSame(2, (int) $j->unit);
    }

    public function test_unassigned_teacher_cannot_write_feedback_for_another_group(): void
    {
        $this->prepareJourney();
        $this->expectException(HttpException::class);
        app(\App\Services\AcademyStudents::class)->feedback($this->actor(5, 5), 3, [
            'feedback' => 'Unauthorized feedback', 'advance' => 1, 'expected_level' => 1, 'expected_unit' => 1,
        ]);
    }

    public function test_parent_linking_is_idempotent_and_cross_academy_links_are_rejected(): void
    {
        $service = app(\App\Services\AcademyStudents::class);
        $service->linkParent($this->actor(1, 3), 7, 4); $service->linkParent($this->actor(1, 3), 7, 4);
        $this->assertSame(1, DB::table('student_parent_links')->where('student_id', 7)->where('parent_id', 4)->count());
        $this->assertSame([3, 7], app(LearningAccess::class)->students($this->actor(4, 7))->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->all());
        $this->expectException(HttpException::class);
        $service->linkParent($this->actor(1, 3), 6, 4);
    }

    public function test_parent_feedback_read_is_limited_to_linked_children(): void
    {
        $this->prepareJourney();
        app(\App\Services\AcademyStudents::class)->feedback($this->actor(2, 5), 3, ['feedback' => 'Well done!', 'advance' => 0, 'expected_level' => 1, 'expected_unit' => 1]);
        $request = Request::create('/learning/academy/students/3/feedback/1/read', 'POST');
        $request->setUserResolver(fn () => $this->actor(4, 7));
        app(\App\Http\Controllers\Academy\LearnerController::class)->readFeedback($request, 3, 1, app(LearningAccess::class));
        $this->assertNotNull(DB::table('academy_teacher_feedback')->where('id', 1)->value('parent_read_at'));
    }

    public function test_teacher_legacy_detail_guard_blocks_unassigned_student_and_class_urls(): void
    {
        $request = Request::create('/teacher/student/show/details/Account%207');
        $route = new \Illuminate\Routing\Route('GET', 'teacher/student/show/details/{name}', fn () => null);
        $route->bind($request); $request->setRouteResolver(fn () => $route);
        $this->expectException(HttpException::class);
        app(\App\Services\TeacherLearningGuard::class)->check($request, $this->actor(2, 5));
    }

    public function test_admin_student_list_uses_joined_records_without_ambiguous_columns(): void
    {
        $request = Request::create('/learning/academy');
        $request->setUserResolver(fn () => $this->actor(1, 3));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->index($request, app(LearningAccess::class));
        $this->assertSame(2, $view->getData()['students']->total());
        $request->setUserResolver(fn () => $this->actor(4, 7));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->index($request, app(LearningAccess::class));
        $this->assertSame(1, $view->getData()['students']->total());
    }


    public function test_profile_edit_and_parent_unlink_update_original_records_and_revoke_access(): void
    {
        $service = app(\App\Services\AcademyStudents::class);
        $service->updateProfile($this->actor(1, 3), 3, ['firstname' => 'Updated', 'lastname' => 'Child', 'email' => 'updated@example.test', 'mobile_no' => '01000000000']);
        $this->assertDatabaseHas('users', ['id' => 3, 'email' => 'updated@example.test']);
        $this->assertDatabaseHas('userprofiles', ['user_id' => 3, 'firstname' => 'Updated']);
        $service->unlinkParent($this->actor(1, 3), 3, 4);
        $this->assertFalse(app(LearningAccess::class)->students($this->actor(4, 7))->where('users.id', 3)->exists());
        $this->assertDatabaseHas('academy_learning_events', ['student_id' => 3, 'event' => 'parent_unlinked']);
    }

    public function test_group_edit_connects_original_class_labels_units_and_responsible_teacher(): void
    {
        $request = Request::create('/learning/academy/groups/1', 'POST', ['group_name' => 'English Saturday', 'level' => 2, 'teacher_id' => 5, 'schedule' => 'Saturday 5 pm']);
        $request->setUserResolver(fn () => $this->actor(1, 3));
        app(\App\Http\Controllers\Academy\LearnerController::class)->saveGroup($request, 1);
        $this->assertDatabaseHas('groups', ['id' => 1, 'group_name' => 'English Saturday']);
        $this->assertDatabaseHas('academy_group_settings', ['group_id' => 1, 'level' => 2, 'schedule' => 'Saturday 5 pm']);
        $this->assertDatabaseHas('standards_link', ['id' => 1, 'class_teacher_id' => 5]);
        $this->assertSame(2, DB::table('class_teacher_links')->where('standardLink_id', 1)->where('teacher_id', 5)->whereNull('deleted_at')->count());
        $this->assertFalse(app(\App\Services\AcademyAssignments::class)->allows($this->actor(2, 5), 1));
        $this->assertTrue(app(\App\Services\AcademyAssignments::class)->allows($this->actor(5, 5), 1));
    }

    public function test_group_with_students_cannot_be_archived_and_history_is_kept(): void
    {
        $request = Request::create('/learning/academy/groups/1/archive', 'POST');
        $request->setUserResolver(fn () => $this->actor(1, 3));
        try { app(\App\Http\Controllers\Academy\LearnerController::class)->archiveGroup($request, 1); $this->fail('Enrolled group must not be archived.'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('group', $e->errors()); }
        $this->assertDatabaseHas('groups', ['id' => 1, 'deleted_at' => null]);
        $this->assertDatabaseHas('student_academics', ['id' => 1, 'deleted_at' => null]);
    }

    public function test_all_four_roles_can_render_connected_learning_record_without_frontend_build(): void
    {
        $this->prepareJourney();
        $directory = sys_get_temp_dir().'/academy-view-'.uniqid();
        foreach (['layouts/admin', 'layouts/teacher', 'layouts/student', 'academy'] as $path) { mkdir($directory.'/'.$path, 0777, true); }
        foreach (['layouts/admin/layout', 'layouts/teacher/layout', 'layouts/student/layout', 'academy/parent-layout'] as $path) {
            file_put_contents($directory.'/'.$path.'.blade.php', '@yield("content")');
        }
        view()->getFinder()->prependLocation($directory);
        view()->share('errors', new \Illuminate\Support\ViewErrorBag);
        try {
            foreach ([[1, 3], [2, 5], [3, 6], [4, 7]] as [$id, $role]) {
                $request = Request::create('/learning/academy/students/3');
                $request->setUserResolver(fn () => $this->actor($id, $role));
                Auth::setUser($this->actor($id, $role));
                $html = app(\App\Http\Controllers\Academy\LearnerController::class)->show($request, 3, app(LearningAccess::class), app(\App\Services\AcademyStudents::class))->render();
                $this->assertStringContainsString('Learning record', $html);
                $this->assertStringContainsString('Teacher feedback', $html);
                $this->assertSame($role === 3, str_contains($html, 'Placement notes (administration only)'));
                if ($role === 5) { $this->assertStringContainsString('Share feedback', $html); }
            }
        } finally {
            foreach (['layouts/admin/layout', 'layouts/teacher/layout', 'layouts/student/layout', 'academy/parent-layout'] as $path) { unlink($directory.'/'.$path.'.blade.php'); }
            foreach (['layouts/admin', 'layouts/teacher', 'layouts/student', 'academy', 'layouts'] as $path) { rmdir($directory.'/'.$path); }
            rmdir($directory);
        }
    }


    private function openGroupLesson(bool $homework = true): int
    {
        $this->prepareJourney();
        return app(\App\Services\AcademyLessons::class)->start($this->actor(2, 5), 1, [
            'lesson_date' => now('Africa/Cairo')->toDateString(), 'start_time' => '17:00', 'homework_assigned' => $homework, 'topic' => 'Speaking practice',
        ]);
    }

    public function test_group_calendar_saves_days_and_clock_times_in_cairo(): void
    {
        $request = Request::create('/learning/academy/groups/1', 'POST', ['group_name' => 'Group A', 'level' => 1, 'teacher_id' => 2,
            'structured_schedule' => 1, 'meeting_days' => ['Monday', 'Thursday'], 'start_time' => '17:30', 'end_time' => '18:30']);
        $request->setUserResolver(fn () => $this->actor(1, 3));
        app(\App\Http\Controllers\Academy\LearnerController::class)->saveGroup($request, 1);
        $setting = DB::table('academy_group_settings')->where('group_id', 1)->first();
        $this->assertSame(['Monday', 'Thursday'], json_decode($setting->meeting_days, true));
        $this->assertSame('17:30', substr($setting->start_time, 0, 5));
        $this->assertSame('Africa/Cairo', $setting->timezone);
    }

    public function test_admin_adds_existing_students_from_group_without_fabricating_a_placement_test(): void
    {
        $this->prepareJourney();
        app(\App\Services\AcademyLessons::class)->addStudents($this->actor(1, 3), 1, ['students' => [7], 'agree_starting_level' => 1]);
        $this->assertDatabaseHas('academy_student_journeys', ['student_id' => 7, 'placement_status' => 'waived', 'level' => 1]);
        $this->assertDatabaseHas('group_members', ['group_id' => 1, 'member_id' => 7, 'deleted_at' => null]);
        $this->assertDatabaseHas('student_academics', ['standardLink_id' => 1, 'user_id' => 7, 'deleted_at' => null]);
    }

    public function test_bulk_group_add_is_atomic_when_a_foreign_student_is_submitted(): void
    {
        $this->prepareJourney();
        try { app(\App\Services\AcademyLessons::class)->addStudents($this->actor(1, 3), 1, ['students' => [7, 6], 'agree_starting_level' => 1]); $this->fail('Foreign student must fail.'); }
        catch (HttpException $e) { $this->assertSame(404, $e->getStatusCode()); }
        $this->assertDatabaseMissing('group_members', ['member_id' => 7]);
        $this->assertDatabaseMissing('academy_student_journeys', ['student_id' => 7]);
    }

    public function test_lesson_creation_is_idempotent_and_pending_does_not_count_as_absent_or_missing(): void
    {
        $lesson = $this->openGroupLesson();
        $again = app(\App\Services\AcademyLessons::class)->start($this->actor(2, 5), 1, ['lesson_date' => now('Africa/Cairo')->toDateString(), 'start_time' => '17:00', 'homework_assigned' => 1]);
        $this->assertSame($lesson, $again);
        $this->assertDatabaseCount('academy_group_lessons', 1);
        $this->assertDatabaseCount('academy_lesson_students', 1);
        $summary = app(\App\Services\AcademyLessons::class)->summary(10, [3])->get(3);
        $this->assertSame(0, (int) $summary->absent_count);
        $this->assertSame(0, (int) $summary->homework_missing);
        $this->assertSame(1, (int) $summary->homework_pending);
    }

    public function test_teacher_saves_attendance_and_homework_and_parent_sees_exact_child_totals(): void
    {
        $lesson = $this->openGroupLesson(); $service = app(\App\Services\AcademyLessons::class);
        $data = ['students' => [['student_id' => 3, 'attendance' => 'absent', 'homework' => 'missing']]];
        $service->save($this->actor(2, 5), 1, $lesson, $data);
        $service->save($this->actor(2, 5), 1, $lesson, $data);
        $summary = $service->summary(10, [3])->get(3);
        $this->assertSame(1, (int) $summary->absent_count); $this->assertSame(1, (int) $summary->homework_missing);
        $service->save($this->actor(2, 5), 1, $lesson, ['students' => [['student_id' => 3, 'attendance' => 'present', 'homework' => 'done']]]);
        $summary = $service->summary(10, [3])->get(3);
        $this->assertSame(0, (int) $summary->absent_count); $this->assertSame(1, (int) $summary->homework_done);
        $request = Request::create('/learning/academy/students/3'); $request->setUserResolver(fn () => $this->actor(4, 7));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->show($request, 3, app(LearningAccess::class), app(\App\Services\AcademyStudents::class));
        $this->assertSame(1, (int) $view->getData()['lessonSummary']->homework_done);
        $this->assertSame('present', $view->getData()['lessonRecords']->first()->attendance);
        $this->assertDatabaseHas('academy_learning_events', ['student_id' => 3, 'event' => 'lesson_record_updated']);
    }

    public function test_no_homework_assigned_is_not_counted_as_missing(): void
    {
        $lesson = $this->openGroupLesson(false);
        app(\App\Services\AcademyLessons::class)->save($this->actor(2, 5), 1, $lesson, ['students' => [['student_id' => 3, 'attendance' => 'present', 'homework' => 'not_assigned']]]);
        $summary = app(\App\Services\AcademyLessons::class)->summary(10, [3])->get(3);
        $this->assertSame(0, (int) $summary->homework_pending); $this->assertSame(0, (int) $summary->homework_missing);
    }

    public function test_another_teacher_cannot_open_group_or_mark_its_lesson(): void
    {
        $lesson = $this->openGroupLesson();
        $this->expectException(HttpException::class);
        app(\App\Services\AcademyLessons::class)->save($this->actor(5, 5), 1, $lesson, ['students' => [['student_id' => 3, 'attendance' => 'absent', 'homework' => 'missing']]]);
    }

    public function test_teacher_cannot_insert_foreign_students_into_a_saved_lesson_roster(): void
    {
        $lesson = $this->openGroupLesson();
        try { app(\App\Services\AcademyLessons::class)->save($this->actor(2, 5), 1, $lesson, ['students' => [
            ['student_id' => 3, 'attendance' => 'absent', 'homework' => 'missing'], ['student_id' => 6, 'attendance' => 'present', 'homework' => 'done']]]); $this->fail('Foreign student must fail.'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('students', $e->errors()); }
        $this->assertDatabaseHas('academy_lesson_students', ['lesson_id' => $lesson, 'student_id' => 3, 'attendance' => 'pending']);
    }

    public function test_lesson_snapshot_and_parent_history_survive_a_group_transfer(): void
    {
        $lesson = $this->openGroupLesson();
        app(\App\Services\AcademyLessons::class)->save($this->actor(2, 5), 1, $lesson, ['students' => [['student_id' => 3, 'attendance' => 'absent', 'homework' => 'done']]]);
        $group = app(AcademyGroups::class)->create(10, ['group_name' => 'Next group', 'level' => 1, 'teacher_id' => 5]);
        DB::table('academy_group_settings')->insert(['school_id' => 10, 'group_id' => $group->id, 'level' => 1]);
        app(\App\Services\AcademyStudents::class)->assign($this->actor(1, 3), 3, $group->id);
        $this->assertCount(0, app(\App\Services\AcademyLessons::class)->roster($this->actor(2, 5), 1)->get());
        $this->assertDatabaseHas('academy_lesson_students', ['lesson_id' => $lesson, 'student_id' => 3, 'attendance' => 'absent']);
        $this->assertSame(1, (int) app(\App\Services\AcademyLessons::class)->summary(10, [3])->get(3)->absent_count);
    }

    public function test_parent_and_student_cannot_open_teacher_group_tools_or_mark_attendance(): void
    {
        $this->expectException(HttpException::class);
        app(\App\Services\AcademyLessons::class)->group($this->actor(4, 7), 1);
    }

    public function test_teacher_dashboard_opens_only_the_assigned_group_workspace(): void
    {
        config(['academy_backend.enabled' => true]);
        $response = app(\App\Http\Controllers\Teacher\DashboardController::class)->index(Request::create('/teacher/dashboard'));
        $this->assertStringEndsWith('/learning/academy/groups', $response->getTargetUrl());
    }


    public function test_general_feedback_message_needs_no_placement_or_unit_advance_and_is_visible_to_parent(): void
    {
        app(\App\Services\AcademyLessons::class)->message($this->actor(2, 5), 3, ['message' => 'Excellent participation today.']);
        $this->assertDatabaseHas('academy_student_messages', ['student_id' => 3, 'teacher_id' => 2, 'message' => 'Excellent participation today.']);
        $this->assertDatabaseCount('academy_teacher_feedback', 0);
        $request = Request::create('/learning/academy/students/3'); $request->setUserResolver(fn () => $this->actor(4, 7));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->show($request, 3, app(LearningAccess::class), app(\App\Services\AcademyStudents::class));
        $this->assertSame('Excellent participation today.', $view->getData()['teacherMessages']->first()->message);
        $this->assertSame('pending', $view->getData()['journey']->placement_status);
    }

    public function test_unassigned_teacher_cannot_message_another_groups_student(): void
    {
        $this->expectException(HttpException::class);
        app(\App\Services\AcademyLessons::class)->message($this->actor(5, 5), 3, ['message' => 'Not allowed.']);
    }

    public function test_future_lessons_cannot_be_marked_as_past_attendance(): void
    {
        $this->expectException(ValidationException::class);
        app(\App\Services\AcademyLessons::class)->start($this->actor(2, 5), 1, ['lesson_date' => now('Africa/Cairo')->addDays(2)->toDateString(), 'start_time' => '17:00', 'homework_assigned' => 0]);
    }

    public function test_group_and_lesson_native_forms_render_for_admin_and_teacher(): void
    {
        $lesson = $this->openGroupLesson();
        $dir = sys_get_temp_dir().'/academy-group-view-'.uniqid();
        foreach (['layouts/admin', 'layouts/teacher'] as $path) { mkdir($dir.'/'.$path, 0777, true); file_put_contents($dir.'/'.$path.'/layout.blade.php', '@yield("content")'); }
        view()->getFinder()->prependLocation($dir); view()->share('errors', new \Illuminate\Support\ViewErrorBag);
        try {
            foreach ([[1, 3], [2, 5]] as [$id, $role]) {
                $request = Request::create('/learning/academy/groups/1'); $request->setUserResolver(fn () => $this->actor($id, $role));
                $controller = app(\App\Http\Controllers\Academy\GroupLessonController::class);
                $html = $controller->show($request, 1, app(\App\Services\AcademyLessons::class))->render();
                $this->assertStringContainsString('Students in this group', $html);
                $this->assertSame($role === 3, str_contains($html, 'Add existing students'));
                $html = $controller->show($request, 1, app(\App\Services\AcademyLessons::class), $lesson)->render();
                $this->assertStringContainsString('Attendance', $html); $this->assertStringContainsString('Homework', $html);
                $this->assertSame($role === 5, str_contains($html, 'Save attendance & homework'));
            }
        } finally {
            foreach (['layouts/admin', 'layouts/teacher'] as $path) { unlink($dir.'/'.$path.'/layout.blade.php'); rmdir($dir.'/'.$path); }
            rmdir($dir.'/layouts'); rmdir($dir);
        }
    }


    public function test_teacher_dashboard_roster_previews_exclude_other_groups_students(): void
    {
        $group = app(AcademyGroups::class)->create(10, ['group_name' => 'Other teacher group', 'level' => 1, 'teacher_id' => 5]);
        app(AcademyGroups::class)->enroll(10, $group->id, [7]);
        $preview = app(\App\Services\AcademyLessons::class)->rosterPreview($this->actor(2, 5), [1, $group->id]);
        $this->assertSame([3], $preview->get(1)->pluck('id')->map(fn ($id) => (int) $id)->all());
        $this->assertFalse($preview->has($group->id));
        $request = Request::create('/learning/academy/groups'); $request->setUserResolver(fn () => $this->actor(2, 5));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->groups($request);
        $this->assertSame(1, $view->getData()['groups']->total());
        $this->assertSame([1], $view->getData()['groupStudents']->keys()->map(fn ($id) => (int) $id)->all());
    }

    public function test_structured_schedule_requires_valid_days_and_start_time(): void
    {
        $request = Request::create('/learning/academy/groups/1', 'POST', ['group_name' => 'Group A', 'level' => 1, 'teacher_id' => 2,
            'structured_schedule' => 1, 'meeting_days' => ['Not a weekday']]);
        $request->setUserResolver(fn () => $this->actor(1, 3));
        $this->expectException(ValidationException::class);
        app(\App\Http\Controllers\Academy\LearnerController::class)->saveGroup($request, 1);
    }


    public function test_stale_teacher_assignment_from_a_different_period_does_not_grant_child_access(): void
    {
        DB::table('class_teacher_links')->insert(['school_id' => 10, 'academic_year_id' => 20, 'standardLink_id' => 1, 'teacher_id' => 5, 'subject_id' => 1]);
        $this->assertFalse(app(LearningAccess::class)->students($this->actor(5, 5))->where('users.id', 3)->exists());
        $this->assertFalse(app(\App\Services\AcademyAssignments::class)->allows($this->actor(5, 5), 1));
    }


    public function test_admin_group_list_requires_both_level_and_teacher_and_filters_matches(): void
    {
        $this->prepareJourney();
        $controller = app(\App\Http\Controllers\Academy\LearnerController::class);
        foreach ([[], ['level' => 1], ['teacher_id' => 2]] as $filters) {
            $request = Request::create('/learning/academy/groups', 'GET', $filters); $request->setUserResolver(fn () => $this->actor(1, 3));
            $view = $controller->groups($request);
            $this->assertFalse($view->getData()['filtersReady']); $this->assertSame(0, $view->getData()['groups']->total());
        }
        $request = Request::create('/learning/academy/groups', 'GET', ['level' => 1, 'teacher_id' => 2]); $request->setUserResolver(fn () => $this->actor(1, 3));
        $view = $controller->groups($request); $this->assertTrue($view->getData()['filtersReady']); $this->assertSame(1, $view->getData()['groups']->total());
    }

    public function test_assigned_student_search_includes_students_already_in_another_group(): void
    {
        $group = app(AcademyGroups::class)->create(10, ['group_name' => 'Target group', 'level' => 1, 'teacher_id' => 2]);
        DB::table('academy_group_settings')->insert(['school_id' => 10, 'group_id' => $group->id, 'level' => 1]);
        $request = Request::create('/learning/academy/groups/'.$group->id, 'GET', ['assignment' => 'assigned']); $request->setUserResolver(fn () => $this->actor(1, 3));
        $view = app(\App\Http\Controllers\Academy\GroupLessonController::class)->show($request, $group->id, app(\App\Services\AcademyLessons::class));
        $this->assertSame([3], $view->getData()['candidates']->pluck('id')->map(fn ($id) => (int) $id)->all());
        $this->assertSame('Original group', $view->getData()['candidates']->first()->assigned_group);
        $request = Request::create('/learning/academy', 'GET', ['assignment' => 'assigned']); $request->setUserResolver(fn () => $this->actor(1, 3));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->index($request, app(LearningAccess::class));
        $this->assertSame(1, $view->getData()['students']->total());
    }

    public function test_student_registration_saves_personal_learning_links_and_parent_can_read_them(): void
    {
        $id = app(\App\Services\AcademyStudents::class)->register($this->actor(1, 3), ['firstname' => 'Linked', 'email' => 'linked@example.test', 'password' => 'private-test-password',
            'homework_drive_url' => 'https://drive.google.com/drive/folders/homework', 'recordings_url' => 'https://drive.google.com/drive/folders/recordings', 'report_card_url' => 'https://docs.google.com/spreadsheets/d/report/edit']);
        app(\App\Services\AcademyStudents::class)->linkParent($this->actor(1, 3), $id, 4);
        $request = Request::create('/learning/academy/students/'.$id); $request->setUserResolver(fn () => $this->actor(4, 7));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->show($request, $id, app(LearningAccess::class), app(\App\Services\AcademyStudents::class));
        $this->assertSame('https://drive.google.com/drive/folders/homework', $view->getData()['journey']->homework_drive_url);
        $this->assertSame('https://docs.google.com/spreadsheets/d/report/edit', $view->getData()['journey']->report_card_url);
    }

    public function test_student_link_edit_preserves_progress_and_rejects_unsafe_url_schemes(): void
    {
        $this->prepareJourney(2, 1); $service = app(\App\Services\AcademyStudents::class);
        $service->updateProfile($this->actor(1, 3), 3, ['firstname' => 'Child', 'email' => 'user3@example.test', 'homework_drive_url' => 'https://drive.google.com/drive/folders/personal']);
        $this->assertDatabaseHas('academy_student_journeys', ['student_id' => 3, 'level' => 2, 'homework_drive_url' => 'https://drive.google.com/drive/folders/personal']);
        $this->expectException(ValidationException::class);
        $service->updateProfile($this->actor(1, 3), 3, ['firstname' => 'Child', 'email' => 'user3@example.test', 'recordings_url' => 'javascript:alert(1)']);
    }

    public function test_clock_uses_twelve_hour_display_and_converts_noon_and_midnight(): void
    {
        $this->assertSame('5:30 PM', \App\Support\AcademyClock::time('17:30:00'));
        foreach ([[12, 'AM', '00:15'], [12, 'PM', '12:15'], [5, 'PM', '17:15']] as [$hour, $period, $expected]) {
            $request = Request::create('/', 'POST', ['start_time_hour' => $hour, 'start_time_minute' => 15, 'start_time_period' => $period]);
            \App\Support\AcademyClock::normalize($request, 'start_time'); $this->assertSame($expected, $request->input('start_time'));
        }
    }

    public function test_teacher_leave_is_reviewed_by_admin_and_visible_to_its_owner_only(): void
    {
        $service = app(\App\Services\AcademyTeacherWorkspace::class);
        $id = $service->requestLeave($this->actor(2, 5), ['from_date' => '2026-11-03', 'to_date' => '2026-11-04', 'remarks' => 'Family appointment']);
        $this->assertSame(1, $service->leaves($this->actor(2, 5))->count()); $this->assertSame(0, $service->leaves($this->actor(5, 5))->count());
        $service->review($this->actor(1, 3), $id, ['decision' => 'approved', 'comments' => 'Approved.']);
        $this->assertDatabaseHas('teacher_leave_applications', ['id' => $id, 'status' => 'approved', 'academy_decision' => 'approved', 'approved_by' => 1]);
        $this->assertSame('Approved.', $service->leaves($this->actor(2, 5))->first()->comments);
    }

    public function test_leave_rejection_requires_reason_and_cannot_be_reviewed_twice(): void
    {
        $service = app(\App\Services\AcademyTeacherWorkspace::class);
        $id = $service->requestLeave($this->actor(2, 5), ['from_date' => '2026-11-05', 'to_date' => '2026-11-05', 'remarks' => 'Personal leave']);
        try { $service->review($this->actor(1, 3), $id, ['decision' => 'rejected']); $this->fail('Rejection needs a comment.'); } catch (ValidationException $e) { $this->assertArrayHasKey('comments', $e->errors()); }
        $service->review($this->actor(1, 3), $id, ['decision' => 'rejected', 'comments' => 'Please choose another date.']);
        $this->assertDatabaseHas('teacher_leave_applications', ['id' => $id, 'status' => 'cancelled', 'academy_decision' => 'rejected']);
        $this->expectException(ValidationException::class); $service->review($this->actor(1, 3), $id, ['decision' => 'approved']);
    }

    public function test_teacher_cannot_approve_leave_or_withdraw_another_teachers_request(): void
    {
        $service = app(\App\Services\AcademyTeacherWorkspace::class);
        $id = $service->requestLeave($this->actor(2, 5), ['from_date' => '2026-11-06', 'to_date' => '2026-11-06', 'remarks' => 'Personal leave']);
        try { $service->review($this->actor(2, 5), $id, ['decision' => 'approved']); $this->fail('Teachers cannot approve leave.'); } catch (HttpException $e) { $this->assertSame(403, $e->getStatusCode()); }
        try { $service->withdraw($this->actor(5, 5), $id); $this->fail('Only the owner can withdraw.'); } catch (HttpException $e) { $this->assertSame(404, $e->getStatusCode()); }
        $service->withdraw($this->actor(2, 5), $id); $this->assertDatabaseHas('teacher_leave_applications', ['id' => $id, 'status' => 'cancelled']);
    }

    public function test_overlapping_leave_and_foreign_academy_review_are_rejected(): void
    {
        $service = app(\App\Services\AcademyTeacherWorkspace::class);
        $id = $service->requestLeave($this->actor(2, 5), ['from_date' => '2026-11-07', 'to_date' => '2026-11-09', 'remarks' => 'Personal leave']);
        try { $service->requestLeave($this->actor(2, 5), ['from_date' => '2026-11-08', 'to_date' => '2026-11-10', 'remarks' => 'Overlap']); $this->fail('Overlapping requests must fail.'); } catch (ValidationException $e) { $this->assertArrayHasKey('from_date', $e->errors()); }
        $this->expectException(HttpException::class); $service->review($this->actor(1, 3, 20), $id, ['decision' => 'approved']);
    }

    public function test_teacher_attendance_is_self_only_and_full_day_writes_are_idempotent(): void
    {
        $service = app(\App\Services\AcademyTeacherWorkspace::class);
        $input = ['teacher_id' => 2, 'date' => now('Africa/Cairo')->toDateString(), 'session' => 'day', 'status' => 0, 'remarks' => 'Absent'];
        $service->markAttendance($this->actor(1, 3), $input); $service->markAttendance($this->actor(1, 3), $input);
        $this->assertSame(2, $service->attendance($this->actor(2, 5), 5)->count());
        $this->assertSame(0, $service->attendance($this->actor(5, 5), 2)->count());
        $this->assertDatabaseCount('attendances', 2);
        $this->expectException(HttpException::class); $service->markAttendance($this->actor(2, 5), $input);
    }

    public function test_demo_command_creates_only_four_new_linked_accounts_and_preserves_existing_data(): void
    {
        $before = DB::table('users')->count();
        $this->artisan('academy:demo-accounts', ['--school' => 10])->assertExitCode(0);
        $this->assertSame($before + 4, DB::table('users')->count());
        $this->assertSame(4, DB::table('users')->whereIn('email', config('academy_backend.demo_emails'))->count());
        $student = DB::table('users')->where('email', config('academy_backend.demo_emails.6'))->first();
        $parent = DB::table('users')->where('email', config('academy_backend.demo_emails.7'))->first();
        $this->assertDatabaseHas('student_parent_links', ['student_id' => $student->id, 'parent_id' => $parent->id]);
        $this->assertDatabaseHas('academy_group_settings', ['school_id' => 10, 'is_demo' => 1]);
        $this->artisan('academy:demo-accounts', ['--school' => 10])->assertExitCode(0);
        $this->assertSame($before + 4, DB::table('users')->count());
    }

    public function test_test_only_mode_filters_existing_students_and_rejects_other_logins(): void
    {
        $this->artisan('academy:demo-accounts', ['--school' => 10])->assertExitCode(0);
        config(['academy_backend.test_only' => true]);
        $this->assertSame(1, app(LearningAccess::class)->students($this->actor(1, 3))->count());
        $this->assertSame(config('academy_backend.demo_emails.6'), app(LearningAccess::class)->students($this->actor(1, 3))->first()->email);
        DB::table('users')->where('id', 2)->update(['password' => \Illuminate\Support\Facades\Hash::make('old-password-123')]);
        $this->post('/login', ['email' => 'user2@example.test', 'password' => 'old-password-123'])->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', ['id' => 2, 'deleted_at' => null]);
    }


    public function test_legacy_group_without_level_remains_accessible_for_setup_after_selecting_teacher(): void
    {
        $request = Request::create('/learning/academy/groups', 'GET', ['level' => 'unconfigured', 'teacher_id' => 2]);
        $request->setUserResolver(fn () => $this->actor(1, 3));
        $view = app(\App\Http\Controllers\Academy\LearnerController::class)->groups($request);
        $this->assertTrue($view->getData()['filtersReady']); $this->assertSame(1, $view->getData()['groups']->total());
    }

    public function test_native_teacher_workspace_routes_render_with_auth_and_post_leave_decisions(): void
    {
        $directory = sys_get_temp_dir().'/academy-workspace-view-'.uniqid();
        foreach (['layouts/admin', 'layouts/teacher'] as $path) { mkdir($directory.'/'.$path, 0777, true); file_put_contents($directory.'/'.$path.'/layout.blade.php', '@yield("content")'); }
        view()->getFinder()->prependLocation($directory);
        try {
            $teacher = \App\Models\User::without(['members','children','parents'])->findOrFail(2);
            $this->actingAs($teacher)->get('/learning/academy/groups')->assertOk()->assertSee('My groups');
            $this->get('/learning/academy/teacher/leaves')->assertOk()->assertSee('My leave requests');
            $this->get('/learning/academy/teacher/attendance')->assertOk()->assertSee('My attendance');
            $this->get('/teacher/changepassword')->assertOk(); $this->get('/teacher/changeavatar')->assertOk();
            $this->post('/learning/academy/teacher/leaves', ['from_date' => '2026-12-03', 'to_date' => '2026-12-04', 'remarks' => 'Family appointment'])->assertRedirect();
            $leave = DB::table('teacher_leave_applications')->first(); $this->assertNotNull($leave);
            $admin = \App\Models\User::without(['members','children','parents'])->findOrFail(1);
            $this->actingAs($admin)->get('/learning/academy/teacher/leaves')->assertOk()->assertSee('Family appointment');
            $this->post('/learning/academy/teacher/leaves/'.$leave->id.'/review', ['decision' => 'approved', 'comments' => 'Accepted'])->assertRedirect();
            $this->actingAs($teacher)->get('/learning/academy/teacher/leaves')->assertOk()->assertSee('Accepted');
            $this->actingAs($admin)->get('/learning/academy/groups')->assertOk()->assertSee('Select a level and teacher first');
        } finally {
            foreach (['layouts/admin', 'layouts/teacher'] as $path) { unlink($directory.'/'.$path.'/layout.blade.php'); rmdir($directory.'/'.$path); }
            rmdir($directory.'/layouts'); rmdir($directory);
        }
    }

    public function test_unlinked_parent_cannot_read_other_students_personal_drive_links(): void
    {
        app(\App\Services\AcademyStudents::class)->updateProfile($this->actor(1, 3), 7, ['firstname' => 'Other child', 'email' => 'user7@example.test', 'homework_drive_url' => 'https://drive.google.com/drive/folders/private']);
        $this->actingAs($this->actor(4, 7))->get('/learning/academy/students/7')->assertNotFound()->assertDontSee('folders/private');
    }

    public function test_demo_preparation_rejects_reserved_email_in_another_academy_without_partial_creation(): void
    {
        DB::table('users')->insert(['school_id' => 20, 'usergroup_id' => 7, 'name' => 'Conflicting account', 'email' => config('academy_backend.demo_emails.7')]);
        $before = DB::table('users')->count();
        $this->artisan('academy:demo-accounts', ['--school' => 10])->assertExitCode(1);
        $this->assertSame($before, DB::table('users')->count());
        $this->assertDatabaseMissing('users', ['email' => config('academy_backend.demo_emails.3')]);
    }

}
