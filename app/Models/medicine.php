<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicine extends Model
{
    use HasFactory;
    protected $fillable = [ 'scientific_name' , 'commercial_name' , 'manufacturer' , 'medicine_quantity' , 'expiry_date' , 'price'];
    public function category()
    {
        return $this->belongsTo(category::class);
    }
    public function favourite()
    {
        return $this->hasMany(favourite::class);
    }
    public function order_details()
    {
        return $this->hasMany(order_details::class);
    }
    public function stock()
    {
        return $this->belongsToMany(Stock::class)->withPivot('medicine_quantity');
    }
}