<?php

namespace Database\Factories;

use App\Models\LibraryCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryCardFactory extends Factory
{
    protected $model = LibraryCard::class;

    public function definition()
    {
        do {
            $number = $this->faker->numberBetween(1000, 9000);
        } while (LibraryCard::where('library_card_no', $number)->exists());

        return [
            'library_card_no' => $number,
            'book_limit' => 5,
            'expiry_date' => '2026-03-30 00:00:00',
            'status' => 1,
        ];
    }
}
