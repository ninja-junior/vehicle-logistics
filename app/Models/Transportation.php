<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable=['vendor_id','stock_id','origin_id','destination_id','booking_number','carrier_number','driver_name',
    'depature_time','arrival_time','received_by','photo','route_description','note']; 

    public function vendor():BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
    
    public function stock():BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function origin():BelongsTo
    {
        return $this->belongsTo(Location::class,'origin_id');
    }

    public function destination():BelongsTo
    {
        return $this->belongsTo(Location::class,'destination_id');
    }
}
