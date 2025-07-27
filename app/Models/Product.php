<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'image_urls' => 'array',
        'nutrient_levels'  => 'array',
        'nutrient_table'   => 'array',
        'ingredients_info' => 'array',
        'data_sheet' => 'array',
    ];


    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
