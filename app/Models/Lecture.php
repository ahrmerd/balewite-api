<?php

namespace App\Models;

use App\Traits\HasRange;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory, HasRange;
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function day()
    {
        return $this->belongsTo(Day::class);
    }
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
