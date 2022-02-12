<?php

namespace App\Models;

use App\Traits\HasRange;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory, HasRange;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
