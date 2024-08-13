<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
    use HasFactory;
    protected $fillable = [ 'stock_quantity' , 'address_stock' , 'quantity_added' ];
    public function medicine()
    {
        return $this->belongsToMany(Medicine::class)->withPivot('medicine_quantity')->withTimestamps();;
    }
    public function store()
    {
        return $this->belongsTo(store::class);
    }
    public function order_details()
    {
        return $this->hasMany(order_details::class);
    }
}