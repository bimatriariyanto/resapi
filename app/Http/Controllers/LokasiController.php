<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function getLokasi(Request $req)
    {
        // Request provinsi atau kabupaten Kota
        $prov = $req->prov;
        $kabkota = $req->kabkota;

        // Untuk mendapatkan data provinsi
        $listLokasi = Lokasi::select('prov')->groupBy('prov')->get();
        // Jika request merupakan provinsi
        if ($prov != null) {
            $listLokasi = Lokasi::select('prov', 'kabkota')->where('prov', '=', $prov)->get();
        }
        // Jika request merupakan kabupaten atau Kota
        if ($kabkota != null) {
            $listLokasi = Lokasi::select('id', 'kec', 'kabkota', 'prov')->where('kabkota', '=', $kabkota)->get();
        }

        return response()->json($listLokasi);
    }
}
