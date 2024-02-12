<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BreakingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $scheduled_date = $this->faker->dateTimeBetween('+1day', '+2day');

        return [
            'work_id' => $this->faker->numberBetween(1, 10),
            'start_time' => $scheduled_date->format('Y-m-d H:i:s'),
            'end_time' => $scheduled_date->modify('+8hour')->format('Y-m-d H:i:s'),
        ];
    }
}
