<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $guarded = [ ];
    public function order_details()
    {
        return $this->hasMany(order_details::class);
    }
    public function pharmacist()
    {
        return $this->belongsTo(pharmacist::class);
    }
}
