<?php

namespace App\Models;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class store extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $fillable = [ 'username_store' , 'password_store' , 'phone_number_store' ];
    public function order()
    {
        return $this->hasMany(order::class);
    }
    public function stock()
    {
        return $this->hasMany(stock::class);
    }
    public function isStore()
    {
        // Assuming 'role' is a column in the storeOwner table
        return $this->role === 'store';
    }

}
