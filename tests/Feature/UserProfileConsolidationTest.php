<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Models\Userprofile;
use App\Services\UserProfileWriterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Covers UserProfileWriterService, consolidated from 5 near-identical
 * UserProfileController copies. One bug found and fixed during that
 * consolidation: Accountant/Receptionist/Teacher's updatechangeavatar()
 * computed the uploaded image's real type (jpg/jpeg/png - all three are
 * allowed by validation) into $image_type and then never used it - every
 * avatar was saved with a hardcoded ".jpg" filename regardless of its
 * real type.
 */
class UserProfileConsolidationTest extends TestCase
{
    use RefreshDatabase;

    private function makeTeacherWithProfile(): array
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        $profile = Userprofile::create([
            'user_id' => $teacher->id,
            'school_id' => $school->id,
            'usergroup_id' => $teacher->usergroup_id,
            'firstname' => 'Test',
            'lastname' => 'Teacher',
        ]);

        return [$teacher, $profile];
    }

    public function test_avatar_upload_preserves_the_real_image_extension(): void
    {
        Storage::fake('local');

        [$teacher, $profile] = $this->makeTeacherWithProfile();

        $base64Avatar = 'data:image/png;base64,'.base64_encode('fake-png-bytes');

        $result = app(UserProfileWriterService::class)->updateAvatarFromBase64($teacher->id, $base64Avatar);

        $this->assertNotNull($result);

        $storedPath = $profile->fresh()->avatar;

        $this->assertStringEndsWith('.png', $storedPath);
        Storage::disk('local')->assertExists($storedPath);
    }

    public function test_avatar_upload_still_works_for_a_jpg_image(): void
    {
        Storage::fake('local');

        [$teacher, $profile] = $this->makeTeacherWithProfile();

        $base64Avatar = 'data:image/jpg;base64,'.base64_encode('fake-jpg-bytes');

        app(UserProfileWriterService::class)->updateAvatarFromBase64($teacher->id, $base64Avatar);

        $this->assertStringEndsWith('.jpg', $profile->fresh()->avatar);
    }

    public function test_change_password_updates_the_hashed_password(): void
    {
        [$teacher] = $this->makeTeacherWithProfile();

        $result = app(UserProfileWriterService::class)->changePassword($teacher->id, 'a-new-password');

        $this->assertNotNull($result);
        $this->assertTrue(\Hash::check('a-new-password', $teacher->fresh()->password));
    }
}
