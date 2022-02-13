<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="Faculty"),
 *
 * required={"faculty"},
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="faculty", type="string"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * )
 */

class Faculty extends Model
{
    use HasFactory, HasRange;

    public function departments()
    {
        return $this->belongsTo(Department::class);
    }
}
