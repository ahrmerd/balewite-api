<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Messaage"),
 *
 * required={"message", "user_id"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="message", type="string"),
 * @OA\Property(property="user_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */
class Message extends Model
{
    use HasFactory, HasRange;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
