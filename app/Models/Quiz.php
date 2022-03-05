<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Quiz"),
 * required={"title", "course_id", "year"},
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="year", type="string"),
 * @OA\Property(property="course_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Quiz extends Model
{
    use HasFactory, HasRange;


    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function scopeFilterByDepartment($query)
    {
        $department = request('filter')['department'] ?? false;
        // $department = Department::query()->where('department','=', $department)->firstOrFail();
        // $q2 = $query->where('department_id', '=', $query->department()->id);
        if ($department) {
            return $query->when($department, fn (Builder $query) => $query
                ->with(['department' => fn ($query) => $query
                    ->where('department', '=', $department),])
                ->whereHas('department', fn ($query) =>
                $query
                    ->where('department', '=', $department)));
        }
    }
}
