<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RangeAndSort;

class Article extends Model
{
    use HasFactory, HasRange;


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
