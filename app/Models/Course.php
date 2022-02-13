<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 * @OA\Xml(name="Course"),
 *
 * required={"code", "level_id", "name"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="name", type="string", description="the course title or course name"),
 * @OA\Property(property="code", type="string", description="the course code"),
 * @OA\Property(property="level_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */

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
