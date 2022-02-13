<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Day"),
 * required={"day"},
 *
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="day", type="string"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Day extends Model
{
    use HasFactory;

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
