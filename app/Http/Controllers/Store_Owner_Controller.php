<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Store_Owner_Controller extends Controller
{
    public function register_store(Request $request)
    {
        //validation
        $request->validate(
            [
                "username_store" => "required" ,
                "password_store" => "required" ,
                "phone_number_store" => "required" 
            ]
        );
        
        //create data
        $store = new store();
        $store -> username_store = $request -> input("username_store");
        $store -> password_store = Hash::make($request->password_store);
        $store -> phone_number_store = $request -> input("phone_number_store") ;

        $store -> save();

        $token = $store->createToken("auth_token")->plainTextToken;

        // return response 
        return response()->json([
            "message" => "store owner registered successfully!",
            "access_token" => $token,
            "store" => $store,
            ], 201);
        
        // return $request;
    }

    public function login_store(Request $request)
    {
        // validation 
        $request->validate([
            'username_store' => 'required',
            'password_store' => 'required'
        ]);

        // get the data 
        $store = Store::where('username_store', '=', $request->username_store)->first();

/*
        $credentials = $request->only('username_store', 'password_store');

        if (Auth::attempt($credentials)) {
            
            // Authentication passed
            return response()->json(['message' => 'Login successful']);
        }
*/
        if (isset($store->id)) {

            // Check if the provided password matches the hashed password in the database
            if (Hash::check($request->password_store, $store->password_store)) {

                // Generate a new personal access token for the pharmacist
                $token = $store->createToken("auth_token")->plainTextToken;
        
                // Return a success response with the generated token
                return response()->json([
                    "message" => "Store owner logged in successfully!",
                    "access_token" => $token
                ]);

            } else {
                // Password didn't match
                return response()->json([
                    "message" => "Password didn't match"
                ], 401);
            }

        } else {
            // Store owner not found
            return response()->json(
                [
                "message" => "Store Owner was not found"
                ], 404);
        }

    }
    public function profile_store()
    {
        return response() -> json(
            [
               // "status" => 1,
                "message" => "store owner's profile information!",
                "data" => auth() -> user() 
            ]
            );
    }
    public function logout_store()
    {
        auth() -> user() -> tokens -> each(function($token , $key)
        {
            $token -> delete();
        });
        return response() -> json(
            [
                "status" => 1,
                "message" => "store owner logged out successfuly"
            ]
            );
    }
}
