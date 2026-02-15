<?php

namespace Database\Factories;

use App\Models\Income;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Income::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'gopay' => $this->faker->randomFloat(2, 0, 10000),
            'cash' => $this->faker->randomFloat(2, 0, 10000),
            'bsi' => $this->faker->randomFloat(2, 0, 10000),
            'total' => $this->faker->randomFloat(2, 0, 30000),
            'tanggal_income' => $this->faker->date(),
            'month_year' => $this->faker->date('Y-m-01'), // Always set the day to the first of the month
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
