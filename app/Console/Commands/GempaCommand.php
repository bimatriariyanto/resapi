<?php

namespace App\Console\Commands;

use App\Models\Gempa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GempaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:gempa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Earthquake Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = "https://bmkg-content-inatews.storage.googleapis.com/warninggeof.xml";
        $sUrl = file_get_contents($url, False);
        $gempa = simplexml_load_string($sUrl);
        $gempa['tanggal'] = $gempa->info->date;
        $gempa['sent'] = $gempa->sent;
        $jam = str_replace('T', ' ', $gempa['sent']);
        $tanggal = str_replace('WIB', '', $jam);
        $latitude = $gempa->info->latitude;
        $longitude = $gempa->info->longitude;
        $coordinates = $gempa->info->point->coordinates;
        $magnitude = $gempa->info->magnitude;
        $kedalaman = $gempa->info->depth;
        $area = $gempa->info->area;
        $description = $gempa->info->description;
        $headline = $gempa->info->headline;
        $potential = $gempa->info->potential;
        $instruksi = $gempa->info->instruction;
        $subject = $gempa->info->subject;
        $felt = $gempa->info->felt;
        $shakemap = $gempa->info->shakemap;

        $cek = DB::table('gempas')->where('date', $tanggal)->limit(1)->count();
        if ($cek < 1) {
            $data = new Gempa();
            $data->date = $tanggal;
            $data->coordinates = $coordinates;
            $data->latitude = $latitude;
            $data->longitude = $longitude;
            $data->magnitude = $magnitude;
            $data->depth = $kedalaman;
            $data->area = $area;
            $data->potential = $potential;
            $data->subject = $subject;
            $data->headline = $headline;
            $data->instruction = $instruksi;
            $data->description = $description;
            $data->felt = $felt;
            $data->shakemap = $shakemap;
            $data->save();
        }
    }
}
