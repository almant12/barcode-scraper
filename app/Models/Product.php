<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'brand',
        'categories',
        'labels',
        'countries_sold',
        'barcode',
        'image_urls',
        'price',
        'nutrient_levels',
        'nutrient_table',
        'ingredients',
        'ingredients_info',
        'source_url',
    ];

    protected $casts = [
        'image_urls'=> 'array',
        'nutrient_levels'  => 'array',
        'nutrient_table'   => 'array',
        'ingredients_info' => 'array',
    ];
}
