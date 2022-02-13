<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 * required={"choice", "quiz_id",},
 * @OA\Xml(name="Choice"),
 *
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="choice", type="string"),
 * @OA\Property(property="quiz_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="is_answer", type="boolean"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Choice extends Model
{
    use HasFactory;

    protected $casts = [
        'is_answer' => 'boolean',
    ];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
