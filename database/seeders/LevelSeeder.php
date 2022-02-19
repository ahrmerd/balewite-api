<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::query()->create(['level' => 100]);
        Level::query()->create(['level' => 200]);
        Level::query()->create(['level' => 300]);
        Level::query()->create(['level' => 400]);
        Level::query()->create(['level' => 500]);
    }
}
