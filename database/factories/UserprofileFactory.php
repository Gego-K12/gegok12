<?php

namespace Database\Factories;

use App\Models\Userprofile;
use Faker\Provider\en_US\Text;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserprofileFactory extends Factory
{
    protected $model = Userprofile::class;

    public function definition()
    {
        $alternate_no = $this->faker->unique()->numerify('#########');

        $gender = $this->faker->randomElement(['male', 'female']);

        $date_of_birth = $this->faker->dateTimeBetween($startDate = '-18 years', $endDate = '-5 years', $timezone = null);

        $blood_group = $this->faker->randomElement(['a+', 'b+', 'o+', 'ab+', 'a-', 'b-', 'o-', 'ab-']);

        $mother_tongue = $this->faker->randomElement(['Tamil', 'Malayalam', 'Telugu', 'Kannada', 'Hindi']);

        $caste = $this->faker->randomElement(['BC', 'BCM', 'FC', 'MBC', 'OBC', 'Others', 'SC', 'SCA', 'ST']);

        $city = $this->faker->randomElement(['Bangalore', 'Chennai', 'Hyderabad', 'Mumbai', 'Thiruvananthapuram']);

        $city_id = $this->faker->randomElement(['12', '24', '25', '15', '13']);

        $state_id = $this->faker->randomElement(['12', '24', '25',  '15', '13']);

        $pincode = $this->faker->unique()->numerify('######');

        $registration_number = $this->faker->unique()->numerify('######');

        $EMIS_number = $this->faker->unique()->numerify('######');

        $joining_date = $this->faker->dateTimeBetween($startDate = '-4 years', $endDate = '-2 years', $timezone = null);

        if ($gender == 'male') {
            $avatar = 'uploads/male.png';
        } elseif ($gender == 'female') {
            $avatar = 'uploads/female.png';
        }

        $this->faker->addProvider(new Text($this->faker));

        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'alternate_no' => $alternate_no,
            'gender' => $gender,
            'date_of_birth' => $date_of_birth,
            'blood_group' => $blood_group,
            'birth_place' => $city,
            'native_place' => $city,
            'mother_tongue' => $mother_tongue,
            'caste' => $caste,
            'address' => $this->faker->address,
            'city_id' => $city_id,
            'state_id' => $state_id,
            'country_id' => 7,
            'pincode' => $pincode,
            'registration_number' => $registration_number,
            'EMIS_number' => $EMIS_number,
            'joining_date' => $joining_date,
            'avatar' => $avatar,
        ];

    }
}
