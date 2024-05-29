<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'price', 'slug', 'stock_quantity', 'description', 'details', 'category_id', 'brand_id', 'image_id', 'is_featured', 'user_id'];
    function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
    function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
