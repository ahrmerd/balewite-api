<?php

namespace App\Models;

use App\Traits\HasRange;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Lecture"),
 *
 * required={"course_id", "day_id", "period_id", "location", "lecturer"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="course_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="day_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="period_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="location", type="string"),
 * @OA\Property(property="lecturer", type="string"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Lecture extends Model
{
    use HasFactory, HasRange;
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function day()
    {
        return $this->belongsTo(Day::class);
    }
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
