<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;

class QuerySorter {

    public function __construct(
        private readonly string $column,
        private readonly bool $isDesc = false,
    ) { }

    public function addSorting(Builder &$query): void
    {
        $this->isDesc
            ? $query->orderByDesc($this->column)
            : $query->orderBy($this->column);
    }
}
