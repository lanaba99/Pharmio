<?php

use App\Models\medicine;
use App\Models\pharmacist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(pharmacist::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(medicine::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('medicine_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
