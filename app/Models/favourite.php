<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favourite extends Model
{
    use HasFactory;
    protected $table = 'favourites';

    protected $fillable = ['medicine_name'];
    public function pharmacist()
    {
        return $this->belongsTo(pharmacist::class);
    }
    public function medicine()
    {
        return $this->belongsTo(medicine::class);
    }
  
}
