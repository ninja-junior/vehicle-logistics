<?php

namespace App\Models;

use App\Models\Vendor;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable=['vendor_id','name','voy','etd','eta','pol','pod'];

    public function vendor():BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items():HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function pol():BelongsTo
    {
        return $this->belongsTo(Location::class,'pol_id');
    }
    public function pod():BelongsTo
    {
        return $this->belongsTo(Location::class,'pod_id');
    }


}
