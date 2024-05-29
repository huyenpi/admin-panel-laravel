<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id', 'status', 'slug', 'description', 'user_id'];
    function children()
    {
        return $this->hasMany(ProductCategory::class);
    }
    function parent()
    {
        return $this->belongsTo(ProductCategory::class);
    }
    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // function products()
    // {
    //     return $this->hasMany(Product::class, 'category_id');
    // }
}
