<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function budgets() : HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
