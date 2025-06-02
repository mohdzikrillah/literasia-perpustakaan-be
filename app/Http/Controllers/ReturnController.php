<?php

namespace App\Http\Controllers;

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
            "book_condition" => "nullable|string"
        ]);
        //2. check validator eror
        if ($validator->fails()){
            return response()->json([
                "success"=> false,
                "messege" => $validator->errors()
            ], 422);
        };

        // Ambil data peminjaman
        $borrowing = Borrowing::findOrFail($request->borrowing_id);

        $borrowingDate = Carbon::parse($borrowing->borrowing_date);
        $returnDate = Carbon::now(); 
        $selisihHari = $borrowingDate->diffInDays($returnDate, false);

        // Logika status
        if ($selisihHari > 7) {
            $status = 'telat ' . floor($selisihHari - 7) . ' hari';
        } elseif ($selisihHari < 7) {
            $status = 'tersisa ' . floor(7 - $selisihHari) . ' hari';
        } else {
            $status = 'dikembalikan tepat waktu';
        }

        // Update status peminjaman
        $borrowing->status = $status;
        $borrowing->save();

        // Simpan data pengembalian
        $return = Returns::create([
            "borrowing_id" => $request->borrowing_id,
            "book_condition" => $request->book_condition,
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
