<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'name_ar',
        'is_drug',
        'price',
        'image',
        'category_name',
        'category_name_ar',
    ];

    protected function casts(): array
    {
        return [
            'is_drug' => 'boolean',
            'price' => 'decimal:2',
        ];
    }
}
