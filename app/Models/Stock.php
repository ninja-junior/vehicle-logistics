<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    public $modelName;

    protected $with=['location','items'];
    protected $fillable=[
        'user_id',
        'brand_id',
        'modell_id',
        'number',
        'vin',
        'engine_power',
        'model_year',
        'type',
        'country',
        'currency',
        'cif_price',
        'group',
        'location_id',       
    ];
    // protected $casts=[
    //     'cif_price'=>'decimal:10,2',
    // ];
    
    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function modell():BelongsTo
    {
        return $this->belongsTo(Modell::class);
    }
    public function location():BelongsTo
    {
        return $this->belongsTo(Location::class);
    } 
    public function items():HasMany
    {
        return $this->hasMany(Item::class);
    } 

    public function sales():HasMany
    {
        return $this->hasMany(Sale::class);
    }
    public function registrations():HasMany
    {
        return $this->hasMany(Registration::class);
    }

    protected $appends = ['stock_vin']; // Add the virtual field to the model

    public function getStockVinAttribute()
    {
        // Generate the virtual stock_vin field by concatenating the number and vin fields
        return  "{$this->attributes['number']}-{$this->attributes['vin']}";
    }

    public static function getVinsNotInItemsAndNotInMyanmar(string $search = '')
    {
        return self::whereNotIn('id', function ($query) {
                $query->select('stock_id')
                    ->from('items');
            })
            ->whereHas('location', function ($query) {
                $query->where('country', '<>', 'Myanmar');
            })
            ->when($search, function ($query, $search) {
                $query->where('stock_vin', 'like', "%{$search}%");
            })
            ->get();
    }

    public static function getStocksWithRoDate($searchQuery = null)
    {
        $query = self::whereHas('items', function ($query) {
            $query->whereNotNull('customs_id');
        });
    
        if ($searchQuery) {
            $query->where('stock_vin', 'LIKE', '%' . $searchQuery . '%');
        }
    
        return $query->get();
    }
    public static function getStocksWithoutSales($searchQuery = null)
    {
        $query = self::whereHas('items', function ($query) {
            $query->whereNotNull('customs_id');
        })->whereNotIn('id', function($subquery) {
            $subquery->select('stock_id')
                ->from('sales');
        });
        
        if ($searchQuery) {
            $query->where('stock_vin', 'LIKE', '%' . $searchQuery . '%');
        }
        
        return $query->get();
    }

    public static function getStocksWithoutRegisteration($searchQuery = null)
    {
        $query = self::whereHas('items', function ($query) {
            $query->whereNotNull('customs_id');
        })->whereNotIn('id', function($subquery) {
            $subquery->select('stock_id')
                ->from('registrations');
        });
        
        if ($searchQuery) {
            $query->where('stock_vin', 'LIKE', '%' . $searchQuery . '%');
        }
        
        return $query->get();
    }

    public static function getModellName($id): ?Modell
    {
        return Stock::findOrFail($id)->modell;
    }

    public static function getBySale($search=null)
    {
        return self::whereHas('sales', function($query) use ($search) {
            $query->where('stock_vin', 'LIKE', '%' . $search . '%');
        })->get();
    }
}
 