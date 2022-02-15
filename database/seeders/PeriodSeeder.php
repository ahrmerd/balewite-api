<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Period::query()->create(['start_time' => 7, 'end_time' => 8]);
        Period::query()->create(['start_time' => 8, 'end_time' => 9]);
        Period::query()->create(['start_time' => 9, 'end_time' => 10]);
        Period::query()->create(['start_time' => 10, 'end_time' => 11]);
        Period::query()->create(['start_time' => 11, 'end_time' => 12]);
        Period::query()->create(['start_time' => 12, 'end_time' => 13]);
        Period::query()->create(['start_time' => 13, 'end_time' => 14]);
        Period::query()->create(['start_time' => 14, 'end_time' => 15]);
        Period::query()->create(['start_time' => 15, 'end_time' => 16]);
        Period::query()->create(['start_time' => 16, 'end_time' => 17]);
        Period::query()->create(['start_time' => 17, 'end_time' => 18]);
    }
}
