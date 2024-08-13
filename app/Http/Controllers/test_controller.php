<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\pharmacist;
use App\Models\medicineStock;
use App\Models\medicine;
use App\Models\stock;

class test_controller extends Controller

{
    public function reviewAndChangeStatus_test(Request $request)
    {
        $request->validate([
            'username_ph' => 'required|exists:pharmacists,username_ph',
            'new_order_status' => 'required|in:shipped,delivered',
        ]);

        $pharmacistUsername = $request->input('username_ph');
        $newOrderStatus = $request->input('new_order_status');

        // Get pharmacist ID based on the username
        $pharmacist = Pharmacist::where('username_ph', $pharmacistUsername)->first();

        if (!$pharmacist) {
            return response()->json(['message' => 'Pharmacist not found'], 404);
        }

        $pharmacistId = $pharmacist->id;

        // Get all orders made by the pharmacist
        $orders = order::where('pharmacist_id', $pharmacistId)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for the pharmacist'], 404);
        }

        // Update the order status for each order
        foreach ($orders as $order) {
            $order->update(['order_status' => $newOrderStatus]);
        }

        return response()->json(['message' => 'Orders status updated successfully']);
    }


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

    public function reviewAndChangeStatus(Request $request)
    {
        $request->validate([
            'username_ph' => 'required|exists:pharmacists,username_ph',
            'new_status' => 'required|in:shipped,delivered',
            'new_payment_status' => 'required|in:paid',
        ]);

        $pharmacistUsername = $request->input('username_ph');
        $newOrderStatus = $request->input('new_status');
        $newPaymentStatus = $request->input('new_payment_status');

        // Get pharmacist ID based on the username
        $pharmacist = Pharmacist::where('username_ph', $pharmacistUsername)->first();

        if (!$pharmacist) {
            return response()->json(['message' => 'Pharmacist not found'], 404);
        }

        $pharmacistId = $pharmacist->id;

        // Get all orders made by the pharmacist
        $orders = Order::where('pharmacist_id', $pharmacistId)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for this pharmacist'], 404);
        }

        // Update the order status for each order
        foreach ($orders as $order) {
            $order->update(['order_status' => $newOrderStatus]);
            $order->update(['payment_status' => $newPaymentStatus]);

        }

        return response()->json(['message' => 'Orders status updated successfully']);
    }
}


 /*   public function getMedicineInStock(Request $request)
    {
        $address_stock = $request->address_stock;

        $stock = stock::where('address_stock', $address_stock)->first();

        if ($stock) 
        {
            $medicineStock = medicine_stock::where('stock_id', $stock->id)
                                        ->with('medicine')
                                        ->get();

            if ($medicineStock->isNotEmpty()) 
            {
                return $medicineStock->map(function ($item)
                {
                    return 
                    [
                        'scientific_name' => $item->medicine->scientific_name,
                        'price' => $item->medicine->price,
                        'medicine_quantity' => $item->medicine_quantity,
                    ];
                }
            );
            } 
            else 
            {
                return response()->json(['message'=>"This address doesn't have any items in stock."],404);
            }
        } 
        else 
        {
            return response()->json(['message'=> "Address not found."]);
        }
    }
}


    
}

