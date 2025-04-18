<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
    public function product():belongsTo
    {
        return $this->belongsTo(Product::class);
    }
    protected $fillable=['cart_id', 'product_id', 'quantity','price'];
}
