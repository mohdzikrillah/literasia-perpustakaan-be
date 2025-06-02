<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable =[
        'borrowing_id', 'book_condition','borrowing_date', 'return_date'
    ];

    public function borrowing(){
        return $this->belongsTo(Borrowing::class);
    }
}
