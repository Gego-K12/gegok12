<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Database\Factories;

use App\Models\User;
use App\Models\Usergroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * Creates a user with default Student role and active status.
     * Automatically ensures the Student usergroup exists in database.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $first = $this->faker->firstName;
        $last = $this->faker->lastName;
        $uniqueId = $this->faker->unique()->numberBetween(1000, 9999);

        // Ensure a default usergroup exists
        $defaultUsergroup = Usergroup::firstOrCreate(
            ['id' => User::STUDENT_USERGROUP_ID],
            ['name' => 'Student'] // Fallback data
        );

        return [
            'name' => $first.' '.$last.$uniqueId,
            'email' => strtolower($first.$last.$uniqueId).'@mailinator.com',
            'mobile_no' => $this->faker->unique()->numerify('##########'),
            'password' => bcrypt('password'),
            'email_verification_code' => str_random(40),
            'registration_number' => $this->faker->unique()->numerify('######'),
            'remember_token' => str_random(10),
            'usergroup_id' => $defaultUsergroup->id,
            'status' => 'active',
            'email_verified' => true,
            'email_verified_at' => Carbon::now(),
        ];
    }

    /**
     * State: School Admin
     *
     * Creates a user with School Admin role (usergroup_id = 3).
     * Automatically ensures the School Admin usergroup exists in database.
     *
     * @return Factory
     */
    public function schoolAdmin()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::SCHOOLADMIN_USERGROUP_ID], ['name' => 'School Admin']);

            return [
                'usergroup_id' => User::SCHOOLADMIN_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Librarian
     *
     * Creates a user with Librarian role (usergroup_id = 8).
     * Automatically ensures the Librarian usergroup exists in database.
     *
     * @return Factory
     */
    public function librarian()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::LIBRARIAN_USERGROUP_ID], ['name' => 'Librarian']);

            return [
                'usergroup_id' => User::LIBRARIAN_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Student
     *
     * Creates a user with Student role (usergroup_id = 6).
     * Automatically ensures the Student usergroup exists in database.
     *
     * @return Factory
     */
    public function student()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::STUDENT_USERGROUP_ID], ['name' => 'Student']);

            return [
                'usergroup_id' => User::STUDENT_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Teacher
     *
     * Creates a user with Teacher role (usergroup_id = 5).
     * Automatically ensures the Teacher usergroup exists in database.
     *
     * @return Factory
     */
    public function teacher()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::TEACHER_USERGROUP_ID], ['name' => 'Teacher']);

            return [
                'usergroup_id' => User::TEACHER_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Accountant
     *
     * Creates a user with Accountant role (usergroup_id = 11).
     * Automatically ensures the Accountant usergroup exists in database.
     *
     * @return Factory
     */
    public function accountant()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::ACCOUNTANT_USERGROUP_ID], ['name' => 'Accountant']);

            return [
                'usergroup_id' => User::ACCOUNTANT_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Parent
     *
     * Creates a user with Parent role (usergroup_id = 7).
     * Automatically ensures the Parent usergroup exists in database.
     *
     * @return Factory
     */
    public function parent()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::PARENT_USERGROUP_ID], ['name' => 'Parent']);

            return [
                'usergroup_id' => User::PARENT_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Receptionist
     *
     * Creates a user with Receptionist role (usergroup_id = 10).
     * Automatically ensures the Receptionist usergroup exists in database.
     *
     * @return Factory
     */
    public function receptionist()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::RECEPTIONIST_USERGROUP_ID], ['name' => 'Receptionist']);

            return [
                'usergroup_id' => User::RECEPTIONIST_USERGROUP_ID,
            ];
        });
    }

    /**
     * State: Stock Keeper
     *
     * Creates a user with Stock Keeper role (usergroup_id = 12).
     * Automatically ensures the Stock Keeper usergroup exists in database.
     *
     * @return Factory
     */
    public function stockKeeper()
    {
        return $this->state(function (array $attributes) {
            Usergroup::firstOrCreate(['id' => User::STOCK_KEEPER_USERGROUP_ID], ['name' => 'Stock Keeper']);

            return [
                'usergroup_id' => User::STOCK_KEEPER_USERGROUP_ID,
            ];
        });
    }
}
