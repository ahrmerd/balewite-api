<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Level"),
 *
 * required={"level"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="level", type="integer", example="300"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Level extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
