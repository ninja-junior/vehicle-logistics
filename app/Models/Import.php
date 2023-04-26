<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Import extends Model
{
    use HasFactory;
    
    protected $fillable=['number','note'];

    protected $table="imports";
    
    

    public function items():HasMany
    {
        return $this->hasMany(Item::class);
    }
}
