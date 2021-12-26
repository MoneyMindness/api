<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function walletItem() : HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
