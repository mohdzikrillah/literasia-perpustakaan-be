<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Returns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    public function index() {
        $return=Returns::with('borrowing')->get();

        if($return->isEmpty()){
            return response()->json([
                "success" => true,
                "messege" => "resource data not found !"
            ], 200);
        }

        return response()->json([
            "success" => true,
            "messege" => "Get all resource",
            "data" => $return
        ], 200);
    }

    public function store(Request $request){
        //1. validator
        $validator = Validator::make($request->all(),[
            'borrowing_id' => 'required|integer|exists:borrowings,id',
        ]);
        //2. check validator eror
        if ($validator->fails()){
            return response()->json([
                "success"=> false,
                "message" => $validator->errors()
            ], 422);
        };

        // Ambil data peminjaman
        $borrowing = Borrowing::findOrFail($request->borrowing_id);

        $borrowingDate = Carbon::parse($borrowing->borrowing_date);
        $returnDate = Carbon::now();
        $selisihHari = $borrowingDate->diffInDays($returnDate, false);

        // Logika status
        if ($selisihHari > 7) {
        $jumlahHariTelat = floor($selisihHari - 7);
        $denda = $jumlahHariTelat * 5000;
        $status = 'telat ' . $jumlahHariTelat . ' hari - Denda Rp' . number_format($denda, 0, ',', '.');
    } else {
        $status = 'dikembalikan';
}

        // Update status peminjaman
        $borrowing->status = $status;
        $borrowing->save();

        // kembalikan junlah peminjaman ke stock buku
        $book = Book::findOrFail($borrowing->book_id);
        $book->available_stock += $borrowing->lostOfBook;
        $book->save();

        // Simpan data pengembalian
        $return = Returns::create([
            "borrowing_id" => $request->borrowing_id,
            "return_date" => $returnDate,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Pengembalian berhasil diproses.",
            "data" => $return,
            "status" => $status
        ], 201);
    }

    //show
    public function show(string $id){
        $return = Returns::find($id);

        if(!$return){
            return response()->json([
                "success"=>false,
                "messege" => "resource not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "messege" => "Get resource",
            "data" => $return
        ]);
    }

    //update
    public function update(Request $request, string $id){
        //1, cari data
        $return = Returns::find($id);
        if(!$return){
            return response()->json([
                "success"=>false,
                "messege" => "resource not found"
            ], 404);
        }
        //2. validator
        $validator = Validator::make($request->all(),[
            'borrowing_id' => 'required|integer|exists:borrowings,id',
            'book_condition' => 'required|integer|max:255'

        ]);

        if($validator->fails()){
            return response()->json([
                "success"=>false,
                "messege" => $validator->errors()
            ], 400);
        }
        //3 siapkan data yang mau diupdate
        $data = [
            "user_id" => $request->user_id,
            "book_id," => $request->book_id,
            "return_date," => $request->return_date,
        ];

        //4, update data
        $return->update($data);
        return response()->json([
            "success" => true,
            "messege" => "resource updated",
            "data" => $return
        ], 200);
    }
     //delete
    public function destroy(string $id){
        $return = Returns::find($id);
        if(!$return){
            return response()->json([
                "success"=>false,
                "messege" => "resourse not found"
            ], 404);
        }
        $return->delete();
        return response() ->json([
            "success" =>true,
            "messege" => "resource deleted",
            "data" => $return
        ], 200);
    }
}
