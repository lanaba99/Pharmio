<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use App\Models\medicine;
use App\Models\store;
use App\Models\pharmacist;
use App\Models\stock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function create_new_category(Request $request) //only for owner 
    {
        //validation 
        $request -> validate(
            [
                "category_name" => "required|unique:categories"
            ]
            );
        
        // Check if the category already exists
        $existingCategory = Category::where('category_name', $request->input('category_name'))->first();

        if ($existingCategory) 
        {
            // Category already exists, return an error response
            return response()->json([
                'errors' => 
                [
                    'category_name' => ['The category name has already been taken.'],
                ],
            ], 422);
        }
        else
        {
            $category = new category;
            $category -> category_name = $request -> category_name ;
            $category -> save();
            
            return response() -> json(
                [
                    'data' => $category 
                ]
            , 201 );

        }
    }

    public function show_all_categories()
    {
        $categories = Category::orderByDesc("created_at") -> get();
        return response() -> json(
            [
                "data" => $categories
            ]
        , 201 );
    }
    public function search_for_category(Request $request)
    {
        //validation
        $request -> validate(
            [
                "category_name" => "required|exists:categories,category_name"
            ]
            );
            $categoryName = $request->input('category_name');
            $category = Category::where('category_name', $categoryName)->first();
    
            // Check if the category exists
            if (!$category) 
            {
                return response()->json(
                    [
                        'errors' => 
                        [
                            'category_name' => ['this Category is not found!'],
                        ],
                    ], 404);
            }
        
            // Return response with category details
            return response() -> json(
                [
                    "data" => $category
                ]
                );
    }
}