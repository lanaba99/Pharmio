<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Pharmacist;
use App\Models\Medicine;
use App\Models\Favourite;

class FavouriteController extends Controller
{
    
   /* public function addToFavorites(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the authenticated pharmacist
        $pharmacist = Auth::guard('pharmacist')->user();

        // Check if the medicine is already in favorites
        if ($pharmacist->favoriteMedicines()->where('medicine_id', $request->medicine_id)->exists()) {
            return response()->json([
                'status' => 0,
                'message' => 'Medicine is already in favorites.',
            ], 400);
        }

        // Add medicine to favorites
        $favoriteMedicine = new FavoriteMedicine([
            'medicine_id' => $request->medicine_id,
        ]);

        $pharmacist->favoriteMedicines()->save($favoriteMedicine);

        return response()->json([
            'status' => 1,
            'message' => 'Medicine added to favorites successfully.',
            'data' => [
                'medicine_id' => $request->medicine_id,
            ],
        ], 200);
    }
}*/

}
