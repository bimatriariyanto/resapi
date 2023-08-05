<?php

namespace App\Http\Controllers;

use App\Models\Gempa;
use Illuminate\Http\Request;

class GempaController extends Controller
{
    public function getGempa()
    {

        $data = Gempa::select('date', 'coordinates', 'latitude', 'longitude', 'magnitude', 'depth', 'area', 'potential', 'subject', 'headline', 'description', 'felt', 'instruction', 'shakemap')->limit(20)->get();

        return response()->json($data);
    }
    public function getLatestGempa()
    {

        $data = Gempa::select('date', 'coordinates', 'latitude', 'longitude', 'magnitude', 'depth', 'area', 'potential', 'subject', 'headline', 'description', 'felt', 'instruction', 'shakemap')
            ->latest()->first();
        return response()->json($data);
    }
}
