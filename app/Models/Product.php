<?php

namespace App\Models;

use App\Models\Concerns\HasKeywordSearch;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'description'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasKeywordSearch;

    /**
     * @return HasMany<Version, $this>
     */
    public function versions(): HasMany
    {
        return $this->hasMany(Version::class);
    }

    /**
     * @return HasOne<Version, $this>
     */
    public function latestVersion(): HasOne
    {
        return $this->hasOne(Version::class)->latestOfMany();
    }

    protected function searchableColumns(): array
    {
        return ['name', 'description', 'id'];
    }
}
