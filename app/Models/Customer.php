<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable=['name','identification','phone','email','address',];

    public function sales():HasMany
    {
     return $this->hasMany(Sale::class);   
    }

}
