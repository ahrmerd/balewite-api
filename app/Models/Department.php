<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Department"),
 *
 * required={"department", "faculty_id",},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="department", type="string"),
 * @OA\Property(property="banner", type="string"),
 * @OA\Property(property="faculty_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */

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


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_department');
    }
}
