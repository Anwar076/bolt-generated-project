<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_item_id',
        'receiver_item_id',
        'status',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function senderItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'sender_item_id');
    }

    public function receiverItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'receiver_item_id');
    }
}
