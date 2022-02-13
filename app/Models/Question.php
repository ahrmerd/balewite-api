<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Question"),
 *
 * required={"question", "quiz_id",},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="question", type="string"),
 * @OA\Property(property="quiz_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Question extends Model
{
    use HasFactory;

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
