<?php

namespace App\Models;

use App\Traits\HasRange;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Material"),
 * required={"course_id", "title", "description", "url"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="course_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="description", type="string"),
 * @OA\Property(property="url", type="string"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */

class Material extends Model
{
    use HasFactory, HasRange;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
