<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Cuaca extends Model
{
    use HasFactory;

    protected $table = 'cuaca';

    public static function kecamatanByKota($kota)
    {
        // Mendapatkan list semua kode kecamatan di kabupaten Kota
        $listKec = DB::table('lokasi')
            ->select('id')
            ->where('kabkota', '=', $kota)
            ->pluck('id');

        // Untuk memastikan bahwa request sudah benar
        if (count($listKec) == 0) {

            $responseApi = [
                "message" => "Kabupaten atau Kota Salah",
                "code" => 404,
                "result" => null
            ];
            return $responseApi;
        }
        $now = Carbon::now('UTC')->format('Y-m-d H:i:s');
        $now3h = Carbon::now('UTC')->addHours(3)->format('Y-m-d H:i:s');


        // Query untuk mendapatkan data cuaca berdasarkan kode kecamatan
        $cuacaTes = DB::table('cuaca')
            ->join('lokasi', 'cuaca.kec_id', '=', 'lokasi.id')
            ->select('lokasi.kec', 'lokasi.kabkota', 'lokasi.prov', 'lokasi.timezone', 'lokasi.lat', 'lokasi.lon', 'kec_id', 'date', 'hu', 't', 'weather', 'wd', 'ws')
            ->whereBetween("date", [$now, $now3h])
            ->whereIn('kec_id', $listKec)
            ->get();

        // Array data hari
        $hari = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        );

        // Kode cuaca (bisa di lihat di data.bmkg.go.id)
        $kodeCuaca = [
            '0' =>  'Cerah',
            '100' =>  'Cerah',
            '1' =>  'Cerah Berawan',
            '101' =>  'Cerah Berawan',
            '2' =>  'Cerah Berawan',
            '102' =>  'Cerah Berawan',
            '3' =>  'Berawan',
            '103' =>  'Berawan',
            '4' =>  'Berawan Tebal',
            '104' =>  'Berawan Tebal',
            '5' => 'Udara Kabur',
            '10' => 'Asap',
            '45' => 'Kabut',
            '60' => 'Hujan Ringan',
            '61' => 'Hujan Ringan',
            '63' => 'Hujan Lebat',
            '80' => 'Hujan Lokal',
            '95' => 'Hujan Petir',
            '97' => 'Hujan Petir'
        ];

        // Melakukan kustomisasi data
        foreach ($cuacaTes as $key => $value) {
            $kec_id = $value->kec_id;
            $datetime = $value->date;
            $time = strtotime($datetime);
            $konvert = $time + (60 * 60 * 7);
            $hasil = date('d-m-Y H:i:s', $konvert);
            $timezone = $value->timezone;
            $kabkota = $value->kabkota;
            $kecamatan = $value->kec;
            $cuacaLat = $value->lat;
            $cuacaLon = $value->lon;

            $pisahdatetime = explode(" ", $hasil);
            $tanggal = $pisahdatetime[0];

            $waktu = $pisahdatetime[1];

            $waktu = date('H:i', strtotime($waktu));

            if ($timezone == 7) {
                $ketWaktu = 'WIB';
            } elseif ($timezone == 8) {
                $ketWaktu = 'WITA';
                $waktu =  date('H:i', strtotime($waktu . '+1 hour'));
            } else {
                $ketWaktu = 'WIT';
                $waktu =  date('H:i', strtotime($waktu . '+2 hour'));
            }

            $ubahWaktu = $waktu . ' ' . $ketWaktu;

            $ubahTanggal =  date('w', strtotime($tanggal));


            $hariini = $hari[$ubahTanggal];

            $t = $value->t;
            $hu = $value->hu;
            $ws = $value->ws;
            $wd = $value->wd;
            $weather = round($value->weather);
            $weatherInfo = $kodeCuaca[$weather];
            $img = 'https://www.bmkg.go.id/asset/img/weather_icon/ID/' . strtolower($weatherInfo) . '-am.png';


            $dataCuaca[] = [
                'idKec' => $kec_id,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'hari' => $hariini,
                'jam' => $ubahWaktu,
                't' => $t,
                'hu' => $hu,
                'ws' => $ws,
                'wd' => $wd,
                'weatherInfo' => $weatherInfo,
                'img' => $img,
                'lat' => $cuacaLat,
                'lon' => $cuacaLon
            ];
        };

        $responseApi = [
            "message" => "Request data cuaca sukses",
            "code" => 200,
            "result" => $dataCuaca
        ];


        return $responseApi;
    }

    public static function byKecId($id)
    {
        if ($id == '') {
            $id = 5005614; //Kecamatan Kemayoran sebagai nilai default
        }
        $lokasi = Lokasi::where('id', $id)->get();


        $now = Carbon::now('UTC')->format('Y-m-d H:i:s');
        $now3h = Carbon::now('UTC')->addHours(3)->format('Y-m-d H:i:s');


        // Query untuk mendapatkan data cuaca berdasarkan kode kecamatan
        $cuacaTes = DB::table('cuaca')
            ->join('lokasi', 'cuaca.kec_id', '=', 'lokasi.id')
            ->select('lokasi.kec', 'lokasi.kabkota', 'lokasi.prov', 'lokasi.timezone', 'lokasi.lat', 'lokasi.lon', 'kec_id', 'date', 'hu', 't', 'weather', 'wd', 'ws')
            ->whereBetween("date", [$now, $now3h])
            ->where('kec_id', $lokasi[0]['id'])
            ->get();


        // Array data hari
        $hari = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        );

        // Kode cuaca (bisa di lihat di data.bmkg.go.id)
        $kodeCuaca = [
            '0' =>  'Cerah',
            '100' =>  'Cerah',
            '1' =>  'Cerah Berawan',
            '101' =>  'Cerah Berawan',
            '2' =>  'Cerah Berawan',
            '102' =>  'Cerah Berawan',
            '3' =>  'Berawan',
            '103' =>  'Berawan',
            '4' =>  'Berawan Tebal',
            '104' =>  'Berawan Tebal',
            '5' => 'Udara Kabur',
            '10' => 'Asap',
            '45' => 'Kabut',
            '60' => 'Hujan Ringan',
            '61' => 'Hujan Ringan',
            '63' => 'Hujan Lebat',
            '80' => 'Hujan Lokal',
            '95' => 'Hujan Petir',
            '97' => 'Hujan Petir'
        ];

        // Melakukan kustomisasi data
        foreach ($cuacaTes as $key => $value) {
            $kec_id = $value->kec_id;
            $datetime = $value->date;
            $time = strtotime($datetime);
            $konvert = $time + (60 * 60 * 7);
            $hasil = date('d-m-Y H:i:s', $konvert);
            $timezone = $value->timezone;
            $kabkota = $value->kabkota;
            $kecamatan = $value->kec;
            $cuacaLat = $value->lat;
            $cuacaLon = $value->lon;

            $pisahdatetime = explode(" ", $hasil);
            $tanggal = $pisahdatetime[0];

            $waktu = $pisahdatetime[1];

            $waktu = date('H:i', strtotime($waktu));

            if ($timezone == 7) {
                $ketWaktu = 'WIB';
            } elseif ($timezone == 8) {
                $ketWaktu = 'WITA';
                $waktu =  date('H:i', strtotime($waktu . '+1 hour'));
            } else {
                $ketWaktu = 'WIT';
                $waktu =  date('H:i', strtotime($waktu . '+2 hour'));
            }

            $ubahWaktu = $waktu . ' ' . $ketWaktu;

            $ubahTanggal =  date('w', strtotime($tanggal));


            $hariini = $hari[$ubahTanggal];

            $t = $value->t;
            $hu = $value->hu;
            $ws = $value->ws;
            $wd = $value->wd;
            $weather = round($value->weather);
            $weatherInfo = $kodeCuaca[$weather];
            $img = 'https://www.bmkg.go.id/asset/img/weather_icon/ID/' . strtolower($weatherInfo) . '-am.png';


            $dataCuaca[] = [
                'idKec' => $kec_id,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'hari' => $hariini,
                'jam' => $ubahWaktu,
                't' => $t,
                'hu' => $hu,
                'ws' => $ws,
                'wd' => $wd,
                'weatherInfo' => $weatherInfo,
                'img' => $img,
                'lat' => $cuacaLat,
                'lon' => $cuacaLon
            ];
        };

        $responseApi = [
            "message" => "Request data cuaca sukses",
            "code" => 200,
            "result" => $dataCuaca
        ];


        return $responseApi;
    }

    public static function byKecIdDetail($id)
    {
        if ($id == '') {
            $id = 5005614; //Kecamatan Kemayoran sebagai nilai default
        }
        $lokasi = Lokasi::where('id', $id)->get();


        $now = Carbon::now('UTC')->format('Y-m-d H:i:s');



        // Query untuk mendapatkan data cuaca berdasarkan kode kecamatan
        $cuacaTes = DB::table('cuaca')
            ->join('lokasi', 'cuaca.kec_id', '=', 'lokasi.id')
            ->select('lokasi.kec', 'lokasi.kabkota', 'lokasi.prov', 'lokasi.timezone', 'lokasi.lat', 'lokasi.lon', 'kec_id', 'date', 'hu', 't', 'weather', 'wd', 'ws')
            ->where("date", ">", $now)
            ->where('kec_id', $lokasi[0]['id'])
            ->get();


        // Array data hari
        $hari = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        );

        // Kode cuaca (bisa di lihat di data.bmkg.go.id)
        $kodeCuaca = [
            '0' =>  'Cerah',
            '100' =>  'Cerah',
            '1' =>  'Cerah Berawan',
            '101' =>  'Cerah Berawan',
            '2' =>  'Cerah Berawan',
            '102' =>  'Cerah Berawan',
            '3' =>  'Berawan',
            '103' =>  'Berawan',
            '4' =>  'Berawan Tebal',
            '104' =>  'Berawan Tebal',
            '5' => 'Udara Kabur',
            '10' => 'Asap',
            '45' => 'Kabut',
            '60' => 'Hujan Ringan',
            '61' => 'Hujan Ringan',
            '63' => 'Hujan Lebat',
            '80' => 'Hujan Lokal',
            '95' => 'Hujan Petir',
            '97' => 'Hujan Petir'
        ];

        // Melakukan kustomisasi data
        foreach ($cuacaTes as $key => $value) {
            $kec_id = $value->kec_id;
            $datetime = $value->date;
            $time = strtotime($datetime);
            $konvert = $time + (60 * 60 * 7);
            $hasil = date('d-m-Y H:i:s', $konvert);
            $timezone = $value->timezone;
            $kabkota = $value->kabkota;
            $kecamatan = $value->kec;
            $cuacaLat = $value->lat;
            $cuacaLon = $value->lon;

            $pisahdatetime = explode(" ", $hasil);
            $tanggal = $pisahdatetime[0];

            $waktu = $pisahdatetime[1];

            $waktu = date('H:i', strtotime($waktu));

            if ($timezone == 7) {
                $ketWaktu = 'WIB';
            } elseif ($timezone == 8) {
                $ketWaktu = 'WITA';
                $waktu =  date('H:i', strtotime($waktu . '+1 hour'));
            } else {
                $ketWaktu = 'WIT';
                $waktu =  date('H:i', strtotime($waktu . '+2 hour'));
            }

            $ubahWaktu = $waktu . ' ' . $ketWaktu;

            $ubahTanggal =  date('w', strtotime($tanggal));


            $hariini = $hari[$ubahTanggal];

            $t = $value->t;
            $hu = $value->hu;
            $ws = $value->ws;
            $wd = $value->wd;
            $weather = round($value->weather);
            $weatherInfo = $kodeCuaca[$weather];
            $img = 'https://www.bmkg.go.id/asset/img/weather_icon/ID/' . strtolower($weatherInfo) . '-am.png';


            $dataCuaca[] = [
                'idKec' => $kec_id,
                'kabkota' => $kabkota,
                'kecamatan' => $kecamatan,
                'hari' => $hariini,
                'jam' => $ubahWaktu,
                't' => $t,
                'hu' => $hu,
                'ws' => $ws,
                'wd' => $wd,
                'weatherInfo' => $weatherInfo,
                'img' => $img,
                'lat' => $cuacaLat,
                'lon' => $cuacaLon
            ];
        };

        $responseApi = [
            "message" => "Request data cuaca sukses",
            "code" => 200,
            "result" => $dataCuaca
        ];


        return $responseApi;
    }
    public static function harvesine($lat, $lon, $datetime)
    {
        $lokasi = Lokasi::select(DB::raw("id, kec, kabkota, prov,timezone, round((6371 * acos(
                cos( radians(lat) )
                * cos( radians( ? ) )
                * cos( radians( ? ) - radians(lon) )
                + sin( radians(lat) )
                * sin( radians( ? ) )
                ) ), 2) as jarak
            "))
            ->orderBy('jarak')
            ->setBindings([$lat, $lon, $lat])
            ->first();
        // $lokasi['background']= url('/img/BG-Provinsi/BG Provinsi-').$lokasi->prov.'.png';
        $timezone = $lokasi->timezone;


        $arrayData = [];
        if ($lokasi) {

            $cuaca = Cuaca::select(['date as utc_datetime', 'hu', 't', 'wd', 'ws', 'weather', DB::raw('DATE(DATE_ADD(date, INTERVAL ' . $timezone . ' HOUR)) AS tgl'), DB::raw('DATE_ADD(date, INTERVAL ' . $timezone . ' HOUR) AS local_datetime')])
                ->where('kec_id', $lokasi->id)
                ->where('date', '>', $datetime)
                ->get();

            foreach ($cuaca as $c) {
                $arrayData[$c['tgl']][] = $c;
            }


            // $warningCuaca = WarningCuaca::where('headline', 'like', '%' . $lokasi->prov)->orderBy('tanggal', 'desc')->first();
            return [
                'lokasi' => $lokasi,
                'cuaca' => array_values($arrayData),
                // 'warningCuaca' => $warningCuaca
            ];
        }

        return response(['error' => true, 'msg' => 'Data tidak ditemukan'], 404);
    }
}
