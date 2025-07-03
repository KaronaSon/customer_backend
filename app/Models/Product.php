<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $keyType = 'int';
    protected $primaryKey = 'pid';
    protected $fillable = [
        // 'cid',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id'
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function category()
    {
        // return $this->belongsTo(Category::class);
         return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
