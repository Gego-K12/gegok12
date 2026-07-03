<?php

namespace Database\Factories;

use App\Models\ParentProfile;
use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParentProfileFactory extends Factory
{
    protected $model = ParentProfile::class;

    public function definition()
    {
        $qualification = Qualification::where('status', 1)
            ->where('type', '!=', 'teacher')
            ->pluck('id')
            ->toArray();

        $qualification_id = $this->faker->randomElement($qualification);

        $profession = $this->faker->randomElement([
            'business',
            'central_government_employee',
            'private',
            'home_maker',
            'state_government_employee',
            'others',
        ]);

        // default values for home_maker
        $sub_occupation = null;
        $designation = null;
        $organization_name = null;
        $official_address = null;
        $annual_income = null;

        // if not home_maker, fill work details
        if ($profession !== 'home_maker') {
            $sub_occupation = $this->faker->jobTitle;
            $designation = $this->faker->jobTitle;
            $organization_name = $this->faker->company;
            $official_address = $this->faker->randomElement([
                'Bangalore', 'Chennai', 'Hyderabad', 'Mumbai', 'Thiruvananthapuram',
            ]);
            $annual_income = $this->faker->numerify('#######');
        }

        $relation = $this->faker->randomElement(['father', 'mother', 'guardian']);

        return [
            'qualification_id' => $qualification_id,
            'profession' => $profession,
            'sub_occupation' => $sub_occupation,
            'designation' => $designation,
            'organization_name' => $organization_name,
            'official_address' => $official_address,
            'relation' => $relation,
            'annual_income' => $annual_income,
        ];
    }
}
