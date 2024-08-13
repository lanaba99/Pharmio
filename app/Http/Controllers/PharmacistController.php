<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pharmacist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class PharmacistController extends Controller
{
    public function register_ph(Request $request)
    {
        // validation 
        $request->validate(
            [
                "username_ph" => "required" ,
                "password_ph" => "required" ,
                "phone_number_ph" => "required|unique:pharmacists" ,
                "address_ph" => "required"
            ]
        );
        
        //create data
        $pharmacist = new pharmacist();
        $pharmacist -> username_ph = $request -> input("username_ph");
        $pharmacist -> password_ph = Hash::make($request->password_ph);
        $pharmacist -> phone_number_ph = $request -> input("phone_number_ph") ;
        $pharmacist -> address_ph = $request -> input("address_ph"); 

        $pharmacist -> save();

        $token = $pharmacist->createToken("auth_token")->plainTextToken;

        // return response 
        return response()->json([
            "message" => "Pharmacist registered successfully!",
            "access_token" => $token,
            "pharmacist" => $pharmacist,
        ], 201);

    }
    public function login_ph(Request $request)
    {
        // validation 
        $request->validate([
            'username_ph' => 'required',
            'password_ph' => 'required'
        ]);

        // get the data 
        // we are checking the username first and if its's found then we are checking the password
        $pharmacists = Pharmacist::where('username_ph', '=', $request->username_ph)->first();

        if (isset($pharmacists->id)) {

            // Check if the provided password matches the hashed password in the database
            if (Hash::check($request->password_ph, $pharmacists->password_ph)) {

                // Generate a new personal access token for the pharmacist
                $token = $pharmacists->createToken("auth_token")->plainTextToken;
        
                // Return a success response with the generated token
                return response()->json([
                    "message" => "Pharmacist logged in successfully!",
                    "access_token" => $token
                ], 201);

            } else {
                // Password didn't match
                return response()->json([
                    "message" => "wrong username or password, pls check"
                ], 401);
            }

        } else {
            // Pharmacist not found
            return response()->json(
                [
                "message" => "Pharmacist not found"
                ], 404);
        }
        


    }

    public function profile_ph()
    {
        return response() -> json(
            [
               // "status" => 1,
                "message" => "pharmacist's profile information!",
                "data" => auth() -> user() 
            ]
            );
    }
    public function logout_ph()
    {
        auth() -> user() -> tokens -> each (function($token , $key)
        {
            $token -> delete();
        });
        return response() -> json(
            [
                "status" => 1,
                "message" => "pharmacist logged out successfuly"
            ]
            );

    }
}


