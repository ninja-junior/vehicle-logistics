<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    use HasFactory;

    protected $fillable=['name','code','country','city','address','type'];
    

    public function stock():HasOne
    {
        return $this->hasOne(Stock::class,'location_id');
    }

    public static function stockCurrentLocation($id, string $search = null): ?Location
    {
        $query = self::whereHas('stock', function ($query) use ($id) {
                $query->where('id', $id);
            });

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('country', 'like', '%'.$search.'%')
                        ->orWhere('city', 'like', '%'.$search.'%');
                });
        }

        return $query->first();
    }

    public static function locationInMyanmar()
    {
        return self::where('country', 'Myanmar')->get();
    }
}
