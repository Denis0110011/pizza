<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'image', 'description', 'type'];
    public function carts(): HasMany{
    return $this->hasMany(Cart::class);
    }
    public function orderItems(): HasMany{
        return $this->hasMany(OrderItem::class);
    }



}
