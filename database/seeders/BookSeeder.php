<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Mengaji Bukit Mengeja Danau',
            'synopsis' => 'Kumpulan puisi ini merupakan hasil interaksi D. Zawawi Imron dengan alam dan budaya yang berbeda, yang menunjukkan bahwa interaksi tersebut tidak menghambat seorang penyair untuk berkarya.',
            'book_cover' => 'cover-laravel-pemula.jpg',
            'author_id' => 1,
            'category_id' => 1,
            'available_stock' => 10,
        ]);
    }
}
