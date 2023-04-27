<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customs extends Model
{
    use HasFactory;

    protected $fillable=['vendor_id','ro_number','started_at','ro_date','currency','ex_rate','total_taxes'];

    public function items():HasMany
    {
        return $this->hasMany(Item::class);
    }
    public function vendor():BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
