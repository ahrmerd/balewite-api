<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Day::query()->create(['day' => 'monday']);
        Day::query()->create(['day' => 'tuesday']);
        Day::query()->create(['day' => 'wednesday']);
        Day::query()->create(['day' => 'thursday']);
        Day::query()->create(['day' => 'friday']);
        Day::query()->create(['day' => 'saturday']);
        Day::query()->create(['day' => 'sunday']);
    }
}
