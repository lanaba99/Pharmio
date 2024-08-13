<?php

namespace App\Http\Controllers;

use App\Models\medicine;
use App\Models\order;
use App\Models\pharmacist;
use App\Models\stock;
use App\Models\store;
use App\Models\medicine_stock;
use App\Models\order_details;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\ResolveIdenticalPathConflict;


class OrderController extends Controller
{
    // pharmacist making order 
    public function make_order (Request $request)
    {
        $validatedData = $request->validate([
            'order_status' => 'required',
            'payment_status' => 'required',
            'address_stock' => 'required',
            'medicinesData' => 'required|array',
            'medicinesData.*.medicine_id' => 'required|exists:medicines,id',
            'medicinesData.*.order_quantity' => 'required|numeric|min:1',
        ]);
        $stock = stock::where('address_stock', $request->address_stock)->firstOrFail();
        
        $order = order::create([
            'order_date' => Carbon::now(),
            'order_status' => $validatedData['order_status'],
            'payment_status' => $validatedData['payment_status'],
            'stock_id' => $stock->id,
            'pharmacist_id' => Auth::user()->id,
        ]);

        foreach ($validatedData['medicinesData'] as $medicineItem)
        {
            $orderDetail = order_details::create(
                [
                    'order_id' => $order->id,
                    'medicine_id' => $medicineItem['medicine_id'],
                    'order_quantity' => $medicineItem['order_quantity'],
                ]);

            $medicine = Medicine::find($medicineItem['medicine_id']);
            $medicine->decrement('medicine_quantity', $medicineItem['order_quantity']);
        }

        return response()->json(
            [
                'message' => 'Order placed successfully'
            ], 201);
    }



    //pharmacist reviewing his orders 
    public function review_orders(Request $request)
    {
        $pharmacistUsername = $request ['username_ph'];
        $pharmacist = pharmacist::where('username_ph', $pharmacistUsername)->first();
        $pharmacistId = $pharmacist->id;

        $orders = order::where ('pharmacist_id' , $pharmacistId) -> get();

        // $orders = order::with('order_details.medicine_id')
        // ->where('pharmacist_id' , $pharmacistId)
        // ->get();

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
}