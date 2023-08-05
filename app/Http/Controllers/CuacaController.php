<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cuaca;
use Illuminate\Http\Request;

class CuacaController extends Controller
{
    public function apiCuaca(Request $request)
    {
        // Mengambil data kab kota yang di request
        $kabKota = $request->kabkota;

        // Memparshing data ke model Cuaca untuk mendapatkan data cuaca
        $dataCuaca = Cuaca::kecamatanByKota($kabKota);

        // Menampilkan data dalam bentuk json
        return response()->json($dataCuaca);
    }
    public function apiCuacaByIdKec(Request $request)
    {
        // Mengambil data id kecamatan
        $id = $request->id;


        // Memparshing data ke model Cuaca untuk mendapatkan data cuaca
        $dataCuaca = Cuaca::byKecId($id);

        // Menampilkan data dalam bentuk json
        return response()->json($dataCuaca);
    }

    public function apiCuacaDetailByIdKec(Request $request)
    {
        // Mengambil data id kecamatan
        $id = $request->id;


        // Memparshing data ke model Cuaca untuk mendapatkan data cuaca
        $dataCuaca = Cuaca::byKecIdDetail($id);

        // Menampilkan data dalam bentuk json
        return response()->json($dataCuaca);
    }
    public function index(Request $request)
    {
        $cuaca = Cuaca::harvesine(request('lat', -6.165286), request('lon', 106.8532384), request('datetime', date('Y-m-d H:i:s', strtotime('-3 hour'))));
        return response()->json($cuaca);
    }
}
