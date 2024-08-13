<?php

namespace App\Http\Controllers;

use App\Models\medicine;
use App\Models\stock;
use App\Models\store;
use App\Models\order;
use App\Models\pharmacist;
use App\Models\medicineStock;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{
    public function add_to_stock(Request $request)
    {
        // validation
        $request -> validate(
            [
                'address_stock' => 'required|string',
                'medicines' => 'required|array',
                'medicines.*.scientific_name' => 'required|string|exists:medicines,scientific_name',
                'medicines.*.medicine_quantity' => 'required|integer|min:1',
            ]
        );
        
        $addressStock = $request->input('address_stock');
        $stock = Stock::where('address_stock', $addressStock)->first();
        if (!$stock) 
        {
            return response()->json(
                [
                    
                    "status" => 0,
                    "message" => "Stock was not found!"
                ], 404);
        }
        $medicines = $request -> input("medicines");
        foreach ( $medicines as $medItem )
        {
            $medicineScientific = $medItem ["scientific_name"];
            $medQuantity = $medItem ["medicine_quantity"];
            $medicine = medicine::where ("scientific_name",$medicineScientific)->first();
            if ( !$medicine ) 
            {
                return response() -> json(
                    [
                        "status" => 0,
                        "message" => "medicine was not found! "
                    ] , 404
                );
            }
            //$stock = Stock::where('medicine_id', $medicine->id)->first();
            //$stock->medicines() -> attach($medicine->id , ['medicine_quantity'=> $medQuantity]);
            
            $stock->medicine()->syncWithoutDetaching([$medicine->id => ['medicine_quantity' => $medQuantity]]);
            $stock->stock_quantity += $medQuantity;
            $stock->save();
        }
        return response() -> json(
            [
                "status" => 1,
                "message" => "medicines added to stock successfully!",
                "data" => $medicines
            ] ,200
        );
    }
    

    //owner viewing orders 
    public function viewOrders(Request  $request) 
    {
        $pharmacistUsername = $request ['username_ph'];
        $pharmacist = pharmacist::where('username_ph', $pharmacistUsername)->first();
        $pharmacistId = $pharmacist->id;

        $orders = order::where ('pharmacist_id' , $pharmacistId) -> get();
        $ordersDetails = [];
        foreach ($orders as $order)
        {
            $orderDetails[] = [
                'order_id' => $order->id,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'order_date' => $order ->order_date,
                'details' => $order -> order_details -> map(function ($detail)
                {
                    return 
                    [
                        'medicine_id' => $detail->medicine->id,
                        'scientific_name' => Medicine::find ($detail -> medicine_id) -> scientific_name ,
                        'order_quantity' => $detail->order_quantity,
                    ];
                }),
            ];
        }
        return response()->json($orderDetails);
    }


    //owner changing the orders status 
    public function ChangeStatus(Request $request) 
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
        $pharmacist = pharmacist::where('username_ph', $pharmacistUsername)->first();

        if (!$pharmacist) {
            return response()->json(['message' => 'Pharmacist not found'], 404);
        }

        $pharmacistId = $pharmacist->id;

        // Get all orders made by the pharmacist
        $orders = order::where('pharmacist_id', $pharmacistId)->get();

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

    


