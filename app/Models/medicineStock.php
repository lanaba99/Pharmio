<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicineStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_id',
        'medicine_id',
        'medicine_quantity',
    ];
    protected $table = 'medicine_stock';

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
    
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

}
