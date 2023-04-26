<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    protected $fillable=['vendor_id','stock_id','register_name', 'number_plate','currency','rta_tax','regional_code','received_at','expired_at'];

    public function stock():BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function vendor():BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

 
}
