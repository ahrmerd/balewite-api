<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RangeAndSort;

/**
 *
 * @OA\Schema(
 * required={"title", "article", "user_id",},
 * @OA\Xml(name="Article"),
 *
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="article", type="string", description="article body or contents"),
 * @OA\Property(property="priority", type="integer", example=1),
 * @OA\Property(property="user_id", type="integer", readOnly="true", example="1"),
 *
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 *
 * Class Announcement
 */

class Article extends Model
{
    use HasFactory, HasRange;


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
