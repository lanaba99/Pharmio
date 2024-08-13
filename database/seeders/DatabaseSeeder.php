<?php

namespace Database\Seeders;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models;
//use Illuminate\Database\factories\FavouriteFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { 
      \App\Models\category::factory(7)->create();    
      \App\Models\medicine::factory(60)->create();
          
        \App\Models\pharmacist::factory(4)->create();
        \App\Models\store::factory(2)->create();
        \App\Models\stock::factory(3)->create();
        \App\Models\favourite::factory(4)->create();
    }
}
