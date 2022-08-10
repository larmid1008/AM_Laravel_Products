<?php

namespace App\Filters\Product;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ProductPriceFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $min = Str::before($value, '-');
        $max = Str::after($value, '-');
        $query->whereBetween('price', [$min, $max]);
    }
}
