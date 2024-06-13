<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    // private function validateLocationId($id, $type)
    // {
    //     $baseUrl = // url from endpoint location from route api

    //     $url = "{$baseUrl}/{$type}/{$id}.json";
    //     $response = Http::get($url);

    //     return $response->ok() && $response->json('id') === $id;
    // }

    public function updateAccountInfo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'province_id' => 'nullable|integer',
            'regency_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'village_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $user->update($request->all());

        return ResponseFormatter::success($user, 'Account updated');
    }

    public function changePassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $user = Auth::user();

        // Periksa apakah password lama sesuai
        if (!Hash::check($request->old_password, $user->password)) {
            return ResponseFormatter::error(['old_password' => 'Password lama tidak sesuai'], 'Validation Error', 422);
        }

        // Ubah password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return ResponseFormatter::success(null, 'Password berhasil diubah');
    }
}
