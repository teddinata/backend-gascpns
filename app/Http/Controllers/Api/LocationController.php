<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function getProvinces()
    {
        $response = Http::get('https://teddinata.github.io/api-wilayah-indonesia/api/provinces.json');
        return response()->json($response->json());
    }

    public function getRegencies($province_id)
    {
        $response = Http::get("https://teddinata.github.io/api-wilayah-indonesia/api/regencies/{$province_id}.json");
        return response()->json($response->json());
    }

    public function getDistricts($regency_id)
    {
        $response = Http::get("https://teddinata.github.io/api-wilayah-indonesia/api/districts/{$regency_id}.json");
        return response()->json($response->json());
    }

    public function getVillages($district_id)
    {
        $response = Http::get("https://teddinata.github.io/api-wilayah-indonesia/api/villages/{$district_id}.json");
        return response()->json($response->json());
    }
}
