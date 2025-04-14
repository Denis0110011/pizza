<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable=['name',
        'description',
        'price',
        'type'
    ];
    protected $casts=[
        'price'=>'float',
    ];
    public function carts():BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
