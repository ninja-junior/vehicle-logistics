<?php

namespace App\Models;

use App\Events\ModellCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modell extends Model
{
    use HasFactory;

    protected $fillable=['brand_id','name','code','default_engine_power','default_group'];

    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function stocks():HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
