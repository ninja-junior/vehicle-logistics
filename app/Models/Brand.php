<?php

namespace App\Models;

use Ramsey\Uuid\Type\Integer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;
    protected $fillable=['name','code','manufacturer'];
    
    public function modells():HasMany
    {
        return $this->hasMany(Modell::class);
    }

}
