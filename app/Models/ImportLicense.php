<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLicense extends Model
{
    use HasFactory;

    protected $fillable=['vendor_id','number','received_at','expired_at'];

    public function items():HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function vendor():BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

}
