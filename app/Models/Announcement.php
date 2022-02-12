<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
