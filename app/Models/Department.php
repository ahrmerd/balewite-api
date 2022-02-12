<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory, HasRange;

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function material()
    {
        return $this->hasMany(Material::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_department');
    }
}
