<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Outcome;

class OutcomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Outcome::factory()->count(10)->create();
    }
}
