<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Case-insensitive "contains" search across the columns listed in the
 * model's searchableColumns() method.
 */
trait HasKeywordSearch
{
    /**
     * @return list<string>
     */
    abstract protected function searchableColumns(): array;

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $term = trim((string) $term);

        if ($term === '') {
            return;
        }

        $query->where(function (Builder $query) use ($term) {
            foreach ($this->searchableColumns() as $column) {
                $query->orWhereLike($column, "%{$term}%");
            }
        });
    }
}
