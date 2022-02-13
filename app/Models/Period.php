<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Period"),
 *
 * required={"start_time", "end_time"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="start_time", type="string"),
 * @OA\Property(property="end_time", type="string"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Period extends Model
{
    use HasFactory;

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
