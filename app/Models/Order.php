<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'oid';

    protected $fillable = [
        'customer_id',
        'total_amount',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }
    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'order_id');
    }
}