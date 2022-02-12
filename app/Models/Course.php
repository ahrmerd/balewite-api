<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasRange;

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public static function CreateOrSyncCourseDepartment($department_id, $data)
    {
        $code = $data['code'];
        unset($data['code']);
        $course = Course::query()->firstOrCreate(['code' => $code], $data);
        return [$course, $course->departments()->syncWithoutDetaching($department_id)];
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'course_department');
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
