<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 * @OA\Schema(
 * required={"title", "announcement", "user_id",},
 * @OA\Xml(name="Announcement"),
 *
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="announcement", type="string"),
 * @OA\Property(property="priority", type="integer", example=1),
 * @OA\Property(property="user_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="image", type="boolean"),
 * @OA\Property(property="image_url", type="string"),
 *
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 *
 * Class Announcement
 */
class Announcement extends Model
{
    use HasFactory, HasRange;
    // protected $casts = [
    //     'image' => 'boolean',
    // ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
