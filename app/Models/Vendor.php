<?php

namespace App\Models;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable=['name','contact_person','phone','email','appointed_at','terminated_at'];

    protected $casts=[
        'terminated_at'=>'date'
    ];
    public function services():BelongsToMany
    {
        return $this->belongsToMany(Service::class)
        ->withPivot('contracts')
        ->withTimestamps();
    }



    public static function forwardingVendor(string $search = '')
    {
        return self::with('services')
            ->where(function ($query) {
                $query->where('terminated_at', '=', null)
                    ->orWhere('terminated_at', '>', now());
            })
            ->whereHas('services', function ($query) {
                $query->where('code', '=', 'forwarding');
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    }

    public static function importLicenseVendor(string $search = '')
    {
        return self::with('services')
            ->where(function ($query) {
                $query->where('terminated_at', '=', null)
                    ->orWhere('terminated_at', '>', now());
            })
            ->whereHas('services', function ($query) {
                $query->where('code', '=', 'il');
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    }

    public static function customsVendor(string $search = '')
    {
        return self::with('services')
            ->where(function ($query) {
                $query->where('terminated_at', '=', null)
                    ->orWhere('terminated_at', '>', now());
            })
            ->whereHas('services', function ($query) {
                $query->where('code', '=', 'customs');
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    }

    public static function transportationVendor(string $search = '')
    {
        return self::with('services')
            ->where(function ($query) {
                $query->where('terminated_at', '=', null)
                    ->orWhere('terminated_at', '>', now());
            })
            ->whereHas('services', function ($query) {
                $query->where('code', '=', 'transport');
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    }

    public static function rtaVendor(string $search = '')
    {
        return self::with('services')
            ->where(function ($query) {
                $query->where('terminated_at', '=', null)
                    ->orWhere('terminated_at', '>', now());
            })
            ->whereHas('services', function ($query) {
                $query->where('code', '=', 'rta');
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    }

    public function schedules():HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function import_licenses():HasMany
    {
        return $this->hasMany(ImportLicense::class);
    }
    public function customs():HasMany
    {
        return $this->hasMany(Customs::class);
    }
}
