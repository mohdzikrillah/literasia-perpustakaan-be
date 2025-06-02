<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Borrowing::create([
            'user_id'=> 1,
            'book_id' => 1,
            'lostOfBook' => 1,
            'borrowing_date' => Carbon::now()
        ]);
    }
}
