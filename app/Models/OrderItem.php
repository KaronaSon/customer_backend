<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $primaryKey = 'tid'; // Add this line
    public $incrementing = true;   // Add this if tid is auto-increment
    protected $keyType = 'int';
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
{
    return $this->belongsTo(\App\Models\Product::class, 'product_id');
}

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }
}