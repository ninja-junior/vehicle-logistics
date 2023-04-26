<?php

namespace App\Models;

use App\Models\Customs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    
    protected $fillable=['import_id','stock_id','schedule_id','bl_no'];
    protected $with=['schedule'];

    public function import():BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
    public function stock():BelongsTo
    {
        return $this->belongsTo(Stock::class);        
    }
    public function schedule():BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
    public function import_license():BelongsTo
    {
        return $this->belongsTo(ImportLicense::class,'import_license_id');
    }
    public function customs():BelongsTo
    {
        return $this->belongsTo(Customs::class);
    }
}
