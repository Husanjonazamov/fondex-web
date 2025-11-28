<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\VendorUsers;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function deleteUserFromDb(Request $request) {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|exists:vendor_users,uuid',  
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'okay',
                'message' => $validator->errors()->first(), 
            ], 400);
        }
    
        DB::beginTransaction();
    
        try {
            $vendorUser = VendorUsers::where('uuid', $request->uuid)->first();
            if ($vendorUser) {
                $user_id = $vendorUser->user_id;  
                $user = User::find($user_id);
                if ($user) {
                    $user->delete();  
                } else {
                    return response()->json([
                        'status' => 'okay',
                        'message' => 'User not found with the provided user_id.',
                    ], 404);
                }
                $vendorUser->delete();
            } else {
                return response()->json([
                    'status' => 'okay',
                    'message' => 'No associated vendor user found with the provided UUID.',
                ], 404);
            }

            DB::commit();
    
            return response()->json([
                'status' => 'okay',
                'message' => 'User and associated records deleted successfully.',
            ], 200);
    
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user. ' . $e->getMessage(),
            ], 500);
        }
    }
}
