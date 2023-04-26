<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable=['stock_id','customer_id','sales_date','currency','sales_amount','sales_person','is_delivered'];

    public function stock():BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getBySalesStock($search=null)
    {
        return self::whereHas('stock', function($query) use ($search) {
            $query->where('stock_vin', 'LIKE', '%' . $search . '%');
        })->get();
    }
    
}
