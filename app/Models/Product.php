<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'type'];

    protected $casts = ['price' => 'float'];

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePizzas(Builder $query): Builder
    {
        return $query->where('type', 'pizza');
    }

    public function scopeDrinks(Builder $query): Builder
    {
        return $query->where('type', 'drink');
    }
}
