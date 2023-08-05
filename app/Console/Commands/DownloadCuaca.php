<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Cuaca;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;

class DownloadCuaca extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:cuaca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ini merupakan perintah untuk mendownload data cuaca';

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
        $list = [
            'aceh', 'babel', 'bali', 'banten', 'bengkulu', 'gorontalo', 'jakarta', 'jambi', 'jawabarat',
            'jawatengah', 'jawatimur', 'jogyakarta', 'kalbar', 'kalsel', 'kalteng', 'kaltim', 'kaluta',
            'kepriau', 'lampung', 'maluku', 'malut', 'ntb', 'ntt', 'papua', 'papuabarat', 'riau', 'sulbar',
            'sulsel', 'sulteng', 'sultenggara', 'sulut', 'sumbar', 'sumsel', 'sumut'
        ];

        // untuk melakukan truncate data atau menghapus seluruh data cuaca terlebih dahulu
        DB::table('cuaca')->truncate();

        $provs = [
            'aceh', 'babel', 'bali', 'banten', 'bengkulu', 'gorontalo', 'jakarta', 'jambi', 'jawabarat',
            'jawatengah', 'jawatimur', 'jogyakarta', 'kalbar', 'kalsel', 'kalteng', 'kaltim', 'kaluta',
            'kepriau', 'lampung', 'maluku', 'malut', 'ntb', 'ntt', 'papua', 'papuabarat', 'riau', 'sulbar',
            'sulsel', 'sulteng', 'sultenggara', 'sulut', 'sumbar', 'sumsel', 'sumut'
        ];

        foreach ($provs as $prov) {
            Storage::put('cuaca/' . $prov . '.csv', file_get_contents('https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/CSV/kecamatanforecast-' . $prov . '.csv'));

            $pdo = DB::connection()->getPdo();

            $csv_path = Storage::path('') . "cuaca/" . $prov . ".csv";  
            
            if(preg_match("/win.*/i", PHP_OS)) $csv_path = addslashes($csv_path);

            $recordsCount = $pdo->exec("
            LOAD DATA INFILE '" . $csv_path . "'
                INTO TABLE cuaca FIELDS TERMINATED BY ';'
                OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n'
                (@kec_id, @date, @tmin, @tmax, @humin, @humax, @hu, @t, @weather, @wd, @ws) 
                SET kec_id = IF(@kec_id='', NULL,@kec_id),
                DATE = IF(@date='', NULL,@date), 
                tmin = IF(@tmin='', NULL,@tmin), 
                tmax = IF(@tmax='', NULL,@tmax), 
                humin = IF(@humin='', NULL,@humin), 
                humax = IF(@humax='', NULL,@humax), 
                hu = IF(@hu='', NULL,@hu), 
                t = IF(@t='', NULL,@t), 
                weather = IF(@weather='', NULL,@weather), 
                wd = IF(@wd='', NULL,@wd), 
                ws = IF(@ws='', NULL,@ws);
            ");

            echo $recordsCount . "\n";
        }
    }
}
