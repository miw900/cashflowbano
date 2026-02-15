<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Outcome;
use App\Models\Summary;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(SummarySeeder::class);
        // $this->call(IncomeSeeder::class);
        // $this->call(OutcomeSeeder::class);
        // $this->call(OrdersSeeder::class);

        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
        ]);
    }
}
