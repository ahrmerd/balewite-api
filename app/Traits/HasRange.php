<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;
use function PHPUnit\Framework\throwException;

/**
 * Adds the range method for eloquent models
 */
trait HasRange
{
    public function scopeWithRange(Builder $query)
    {

        if (is_string(request('range')) && json_decode(request('range')) && count(json_decode(request('range'))) == 2) {
            $range = json_decode(request('range'));
            $offset = $range[0];
            $limit = $range[1] - $range[0];
            return $query->offset($offset)->limit($limit);
        };
        return $query;
    }

    public function scopeSearchIn(Builder $query, $column)
    {
        $term = request('filter')['q'] ?? false;
        return $query->when($term, fn (Builder $query) => $query->where($column, 'LIKE', '%' . $term . '%'));
    }
}
