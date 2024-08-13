<?php

use App\Models\medicine;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\pharmacist;
use App\Models\stock;
use App\Models\sale_report;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pharmacist::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Stock::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('order_date');
            $table->enum('order_status' , [ 'in preparation','shipped','delivered' ] );
            $table->enum('payment_status' , [ 'paid' , 'unpaid' ] );
            $table->integer('order_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
