<?php

use App\Http\Controllers;
use App\Http\Controllers\Store_Owner_Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*


// csrf token (this has to be written only one time for all the routes that i am making)
Route::get('/get-csrf-token', function () {
    $csrf_token = csrf_token();

    return response()->json(['csrf_token' => $csrf_token]);
});

// register for the store owner
Route::post("/register-store" , [Store_Owner_Controller::class , "register_store"]);


//login route for the store owner
Route::post("/login-store" , [Store_Owner_Controller::class , "login_store"]);


*/