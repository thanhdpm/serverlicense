<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer',
        'product',
        'product_id',
        'key',
        'duration',
        'fingerprint',
        'activated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer' => 'array',
            'product' => 'array',
            'fingerprint' => 'array',
            'activated_at' => 'datetime',
        ];
    }
}
