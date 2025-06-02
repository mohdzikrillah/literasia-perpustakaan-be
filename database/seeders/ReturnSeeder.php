<?php

namespace Database\Seeders;


use App\Models\Returns;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Returns::create([
            'borrowing_id' => 2,
            'borrowing_date' => Carbon::now(),
            'book_condition' => 'tidak ada kerusakan'
        ]);
    }
}
