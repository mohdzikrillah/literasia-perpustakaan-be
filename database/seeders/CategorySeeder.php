<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Puisi',
            'description' => 'Puisi adalah bentuk ekspresi sastra yang menggunakan kata-kata indah dan padat makna untuk menyampaikan perasaan, pemikiran, atau pengalaman batin. Berdasarkan tema dan isi yang dibawanya, puisi dapat dikategorikan ke dalam beberapa jenis seperti puisi cinta, puisi tentang ibu, alam, religi, sosial, patriotik, budaya, hingga puisi yang bersifat filosofis. Setiap kategori memiliki ciri khas tersendiri yang mencerminkan kedalaman emosi, konteks budaya, serta pesan yang ingin disampaikan penyair. Kategori ini membantu pembaca untuk lebih mudah memahami dan mengapresiasi puisi sesuai dengan tema yang diminatinya.'
        ]);
    }
}
