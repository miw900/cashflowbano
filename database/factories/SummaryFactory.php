<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Summary;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Summary>
 */
class SummaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Summary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'month_year' => $this->faker->date('Y-m-01'), // Always set the day to the first of the month
            'total_income' => $this->faker->randomFloat(2, 0, 10000), // Example range for income
            'total_outcome' => $this->faker->randomFloat(2, 0, 10000), // Example range for outcome
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
