<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'phone', 'email', 'address', 'status', 'price'];
    public const STATUTES = [
        'pending' => 'в обработке',
        'processing' => 'готовится',
        'delivering' => 'доставляется',
        'completed' => 'завершен',
        'canceled' => 'отменен',
    ];
    protected $casts = ['price' => 'float'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function updateStatus(string $status):bool
    {
        $result=$this->update(['status' => $status]);

        if($result){
            $this->refresh();
        }
        return $result;

    }
}
