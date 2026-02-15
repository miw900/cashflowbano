<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name(),
            'harga' => $this->faker->randomFloat(2, 1, 10000),
            'catatan' => $this->faker->text(),
            'jam_ambil' => $this->faker->time(),
            'tanggal_ambil' => $this->faker->date(),
            'transaksi' => $this->faker->word(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'tanggal_income' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
