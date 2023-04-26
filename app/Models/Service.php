<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable=['name','code','description'];

    public function vendors():BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)
        ->withPivot('contracts')
        ->withTimestamps();
      
    }
}
