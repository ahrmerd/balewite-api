<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculty = Faculty::query()->create([
            'faculty' => env('ADMIN_FACULTY', 'general')
        ]);
        $department = Department::query()->create([
            'faculty_id' => $faculty->id,
            'department' => env('ADMIN_DEPARTMENT', 'general')
        ]);

        User::query()->create([
            'username' => 'admin',
            'email' => env('ADMIN_EMAIL', 'gizonigeria@gmail.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'phone_number' => env('ADMIN_PHONE', '2349011111'),
            'department_id' => $department->id,
            'authorization_level' => 11,
        ]);
    }
}
