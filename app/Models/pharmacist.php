<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Events\Authenticated;
// use Illuminate\Foundation\Auth\User;

class pharmacist extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $fillable = [ 'username_ph' , 'password_ph' , 'phone_number_ph' , 'address_ph' ];
    
    public function favourite()
    {
        return $this->hasMany(favourite::class);
    }
    public function order()
    {
        return $this->hasMany(order::class);
    }
    public function isPharmacist()
    {
        // Assuming 'role' is a column in the pharmacist table
        return $this->role === 'pharmacist';
    }
}
