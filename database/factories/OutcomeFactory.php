<?php

namespace Database\Factories;

use App\Models\Outcome;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutcomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Outcome::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'total' => $this->faker->randomFloat(2, 0, 10000),
            'keterangan' => $this->faker->text(),
            'tanggal' => $this->faker->date(),
            'month_year' => $this->faker->date('Y-m-01'), // Always set the day to the first of the month
            'tanggal_income' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
