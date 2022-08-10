<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ProductNotDeletedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === true) {
            $query->whereNull('deleted_at');
        }
    }
}
