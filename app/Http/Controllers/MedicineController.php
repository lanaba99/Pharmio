<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\medicine;
use App\Models\category;
use App\Models\store;
use App\Models\stock;
use App\Models\medicineStock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class MedicineController extends Controller
{
    // this fucntion is the first try , it adds a new medicine to only the medicine table 
    // without adding it to the medicineStock table ( so it does not use the stock table to access the medicine_stock tabel)

    // so its  هبد بهبد  unfortunatly!

    /*
    public function add_medicine(Request $request)
    {
        // validation
        $request -> validate(
            [
                "scientific_name" => "required",
                "commercial_name" => "required",
                "category_name" => "required",
                "manufacturer" => "required",
                "medicine_quantity" => "required|integer",
                "expiry_date" => "required|date",
                "price"=> "required|numeric",
                "administration_form" => "required|in:tablets,capsules,syrup,injectable"
            ]
            );
        
        // Find category by name
        $categoryName = $request->input('category_name');

        $category = category::where('category_name', $request->input('category_name'))->first();

        if ($category) 
        {
            // Create a new medicine using the relationship
            $medicineData = $request->only([
                'scientific_name',
                'commercial_name',
                'manufacturer',
                'medicine_quantity',
                'expiry_date',
                'price',
                'administration_form',
            ]);

            $medicine = $category->Medicine()->create($medicineData);

        // return response 
            return response() -> json(
                [
                    "data" => $medicine
                ] , 201 );

        }
        else if (!$category) 
        {
            return response()->json(
                [
                    'errors' => 
                    [
                        'category_name' => ['category does not exist!'],
                    ],
                ]
                , 404);
        }
    }
    */

    public function list_medicines_by_cat_name(Request $request)
    {
        // validation
        $request -> validate(
            [
                "category_name" => "required|exists:categories,category_name"
            ]
            );
        
        $categoryName = $request->input('category_name');
        if(!$categoryName)
        {
            return response() -> json(
                [
                    'errors' => 
                    [
                        'category_name' => ['category does not exist!'],
                    ],
                ]
                 , 401 );
        }
        else 
        {
            $medicines = Medicine::join("categories", "medicines.category_id", "=", "categories.id")
            ->where("categories.category_name", $categoryName)
            ->get();

            return response()->json(
                [
                    "data" => $medicines
                ] , 200
                );
        }
    }
    
    public function med_by_scientific_name(Request $request)
    {
        // validation 
        $request -> validate(
            [
                "scientific_name" => "required|string"
            ]
        );
        $scientificName = $request->input('scientific_name');
        $medicine = medicine::where("scientific_name" , $scientificName) -> first() ;
        if (!$medicine)
        {
            return response() -> json(
                [
                    'errors' => 
                    [
                        'scientific_name' => ['this medicine does not exist!'],
                    ],
                ] , 404 );
        }
        else 
        {
            return response() -> json(
                [
                    "data" => $medicine
                ]
            );
        }
    }

    // this function allows storeOwner with adding medicine to two tables in my database:
    // medicine
    // medicineStock
    // and it uses three table for this action: 
    // tables used: ( medicine - stock - medicineStock )
    public function add_medicine_to_all_stocks(Request $request)
    {
        
        // validation
        $request -> validate(
            [
                "scientific_name" => "required",
                "commercial_name" => "required",
                "category_name" => "required",
                "manufacturer" => "required",
                "medicine_quantity" => "required|integer",
                "expiry_date" => "required|date",
                "price"=> "required|numeric",
                "administration_form" => "required|in:tablets,capsules,syrup,injectable"
            ]
            );
        
        // Find category by name
        $categoryName = $request->input('category_name');

        $category = category::where('category_name', $request->input('category_name'))->first();

        if ($category) 
        {
            // Create a new medicine using the relationship
            $medicineData = $request->only([
                'scientific_name',
                'commercial_name',
                'manufacturer',
                'medicine_quantity',
                'expiry_date',
                'price',
                'administration_form',
            ]);

            $medicine = $category->Medicine()->create($medicineData);

            // get all stocks
            $stocks = Stock::all();
            // get all stocks for the owner
            

        // $storeId = $request->input('store_id');
        // $stocks = Stock::where('store_id', $storeId)->get();


            // iterate through each stock and create a new row in the medicine_stock table
            foreach ($stocks as $stock) {
                medicineStock::create([
                    'medicine_id' => $medicine->id,
                    'stock_id' => $stock->id,
                    'medicine_quantity' => $request->medicine_quantity,
                ]);
            }

            // return response 
            return response() -> json(
                [
                    "data" => $medicine
                ] , 201 );

        }
    }
}



