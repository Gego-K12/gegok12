<?php

namespace Database\Factories;

use App\Models\BackgroundImage;
use App\Models\NoticeBoard;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoticeBoardFactory extends Factory
{
    protected $model = NoticeBoard::class;

    public function definition()
    {

        return [

            'background_id' => BackgroundImage::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence,
            'publish_date' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'expire_date' => $this->faker->dateTimeBetween('now', '+10 days'),
            'description' => $this->faker->paragraph,
            'attachment_file' => null,
        ];
    }
}
