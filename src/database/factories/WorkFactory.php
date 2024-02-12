<?php

namespace Database\Factories;

use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $scheduled_date = $this->faker->dateTimeBetween('+1day', '+7day');

        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'start_time' => $scheduled_date->format('Y-m-d H:i:s'),
            'end_time' => $scheduled_date->modify('+8hour')->format('Y-m-d H:i:s'),
        ];
    }
}
