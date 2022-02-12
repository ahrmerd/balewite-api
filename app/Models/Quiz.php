<?php

namespace App\Models;

use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory, HasRange;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function scopeFilterByDepartment($query)
    {
        $department = request('filter')['department'] ?? false;
        // $department = Department::query()->where('department','=', $department)->firstOrFail();
        // $q2 = $query->where('department_id', '=', $query->department()->id);
        if ($department) {
            return $query->when($department, fn (Builder $query) => $query
                ->with(['department' => fn ($query) => $query
                    ->where('department', '=', $department),])
                ->whereHas('department', fn ($query) =>
                $query
                    ->where('department', '=', $department)));
        }
    }
}
