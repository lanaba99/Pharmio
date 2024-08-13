<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\category;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name');
            $table->string('commercial_name');
            $table->foreignIdFor(Category::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('manufacturer');
            $table->bigInteger('medicine_quantity');
            $table->date('expiry_date');
            $table->float('price');
            $table->enum('administration_form' , [ 'tablets','capsules','syrup' ,'injectable' ] );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
