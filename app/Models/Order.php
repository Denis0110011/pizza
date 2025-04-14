<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public  function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function cart():HasOne
    {
        return $this->hasOne(Cart::class);
    }

}
