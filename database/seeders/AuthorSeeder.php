<?php

namespace Database\Seeders;

use App\Models\Author;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create([
            'name' => 'Zawawi Imron',
            'authors_history' => 'Penyair asal Madura yang dikenal luas karena puisi-puisinya yang menyentuh hati dan kental dengan nuansa lokal. Ia lahir di Sumenep pada tahun 1945 dan dikenal dengan julukan "Penyair Celurit Emas", simbol perpaduan kekuatan dan kelembutan dalam karyanya. Salah satu puisinya yang paling terkenal adalah "Ibuku", yang menggambarkan kasih sayang dan pengorbanan seorang ibu dengan sangat menyentuh. Puisi-puisinya banyak mengangkat tema tentang ibu, tanah kelahiran, budaya Madura, dan nilai-nilai religius. Melalui karya-karyanya, Zawawi berhasil mengangkat kekayaan lokal menjadi inspirasi universal. Ia telah menerima berbagai penghargaan nasional dan internasional, termasuk SEA Write Award dari Thailand'
        ]);
     }
}
