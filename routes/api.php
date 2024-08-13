<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\Store_Owner_Controller;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MedecineArrayController;
use App\Http\Controllers\ShowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// android!!!!!!!!!!!!!!!

Route::post("register-ph" , [PharmacistController::class , "register_ph"]);
Route::post("login-ph" , [PharmacistController::class , "login_ph"]);
Route::get("logout-ph" , [PharmacistController::class , "logout_ph"])
->middleware(["auth:sanctum"])
->name('logout.pharmacist');
Route::get("profile-ph" , [PharmacistController::class , "profile_ph"])
->middleware(["auth:sanctum"])
->name('profile.pharmacist');


Route::post("make-order" , [OrderController::class , "make_order"])
->middleware(["auth:sanctum"]);

Route::post("review-orders" , [OrderController::class , "review_orders"])
->middleware(["auth:sanctum"]);


//here are my problems!!!

// Android 
Route::post("all-stock-med" , [ShowController::class , "getMedicinesInStock"]); // fadi android (retrun a json file)

//web
Route::post("view-orders" , [StockController::class , "viewOrders"]); //store owner API

Route::put("change-order-status" , [StockController::class , "ChangeStatus"]); // store owner  (change orders status)

//test test 
Route::post("/add-medicine-test", [MedicineController::class, "add_medicine_to_all_stocks"]);




//-----------------------------medicine----------------------------------------


Route::post("med-by-cat/{category_name}" , [MedicineController::class , "list_medicines_by_cat_name"]);

Route::post("med-by-name/{scientific_name}" , [MedicineController::class , "med_by_scientific_name"]);

Route::post("/create-category" , [CategoryController::class , "create_new_category"]);

Route::get("/show-all-categories" , [CategoryController::class , "show_all_categories"]);

Route::post("/search-for-category", [CategoryController::class, "search_for_category"]); //no need for auth in header (both can search)







//web!!!!!!!!!!!!!!!!!!!!!!


Route::post("register-store" , [Store_Owner_Controller::class , "register_store"]);


Route::post("/login-store" , [Store_Owner_Controller::class , "login_store"]);

Route::get("logout-store" , [Store_Owner_Controller::class , "logout_store"])
->middleware(["auth:sanctum"])
->name('logout.store');

Route::get("profile-store" , [Store_Owner_Controller::class , "profile_store"])
->middleware(["auth:sanctum"])
->name('profile.store');


Route::post("add-med-to-stock" , [StockController::class , "add_to_stock"]);
Route::post("add-medicine" , [MedicineController::class , "add_medicine"])
->middleware(["auth:sanctum"]);