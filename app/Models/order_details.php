<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_details extends Model
{
    use HasFactory;
    protected $guarded = [ ];
    public function medicine()
    {
        return $this->belongsTo(medicine::class);
    }
    public function order()
    {
        return $this->belongsTo(order::class);
    }
    
    public function stock()
    {
        return $this->belongsToMany(stock::class);
    }
}
