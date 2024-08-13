<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MedicineInStockResource;

use App\Models\order;
use App\Models\medicine;
use App\Models\stock;
use App\Models\medicineStock;
use App\Models\store;
use App\Models\order_details;
use App\Models\pharmacist;


class ShowController extends Controller
{
    
    public function getMedicinesInStock(Request $request)
    {
        $validatedData = $request->validate([
            'address_stock' => 'required',
        ]);

        $stock = Stock::where('address_stock', $validatedData['address_stock'])->firstOrFail();

        $medicineStocks = medicineStock::where('stock_id', $stock->id)->get();

        $medicines = [];

        foreach ($medicineStocks as $medicineStock) {
            $medicine = Medicine::findOrFail($medicineStock->medicine_id);

            $medicines[] = [
                'medicine_id' => $medicine->id,
                'scientific_name' => $medicine->scientific_name,
                'commercial_name' => $medicine->commercial_name,
                'category_id' => $medicine->category_id,
                'manufacturer' => $medicine->manufacturer,
                'medicine_quantity' => $medicineStock->medicine_quantity,
                'expiry_date' => $medicine->expiry_date,
                'price' => $medicine->price,
                'administration_form' => $medicine->administration_form,
            ];
        }

        return response()->json(
            [
                'medicines' => $medicines,
            ], 200);
    }
}

