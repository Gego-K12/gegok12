<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentAcademicFactory extends Factory
{
    protected $model = StudentAcademic::class;

    public function definition()
    {
        $academicYear = AcademicYear::where('status', 1)->first();

        $standardLink = StandardLink::where([
            ['academic_year_id', $academicYear->id],
            ['status', 1],
        ])->pluck('id')->toArray();

        $standardLink_id = $this->faker->randomElement($standardLink);

        $selected_standard = StandardLink::with('standard')
            ->where('id', $standardLink_id)
            ->first();

        // Initialize optional fields
        $board_registration_number = null;
        $transport_details = null;
        $siblings_count = null;
        $sibling_details = null;

        $roll_number = $this->faker->numberBetween(1, 25);
        $id_card_number = $this->faker->numberBetween(1, 25);

        if (in_array($selected_standard->standard->name, ['10', '12'])) {
            $board_registration_number = $this->faker->numerify('########');
        }

        $mode_of_transport = $this->faker->randomElement([
            'auto', 'car', 'city_bus', 'cycle', 'rickshaw', 'school_bus', 'taxi', 'walking',
        ]);

        if (in_array($mode_of_transport, ['auto', 'rickshaw', 'taxi'])) {
            $transport_details = [
                'driver_name' => $this->faker->name,
                'driver_contact_number' => $this->faker->numerify('#########'),
            ];
        }

        $siblings = $this->faker->randomElement(['yes', 'no']);

        if ($siblings === 'yes') {
            $siblings_count = $this->faker->randomElement([1, 2]);
            $sibling_details = [];

            for ($i = 0; $i < $siblings_count; $i++) {
                $sibling_details[$i] = [
                    'sibling_relation' => $this->faker->randomElement(['brother', 'sister']),
                    'sibling_name' => $this->faker->name,
                    'sibling_date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-5 years'),
                    'sibling_standard' => $this->faker->randomElement($standardLink),
                ];
            }
        }

        return [
            // 'roll_number' => $roll_number,
            'id_card_number' => $id_card_number,
            'board_registration_number' => $board_registration_number,
            'mode_of_transport' => $mode_of_transport,
            'transport_details' => $transport_details,
            'siblings' => $siblings,
            'siblings_count' => $siblings_count,
            'sibling_details' => $sibling_details,
        ];
    }
}
