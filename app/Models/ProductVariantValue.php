<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariantValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id',
        'value'
    ];

    public function option()
    {
        return $this->belongsTo(ProductVariantOption::class, 'option_id');
    }

    public function variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_value_pivot'
        );
    }
}
