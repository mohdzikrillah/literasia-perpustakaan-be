<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowingController extends Controller
{
    public function index() {

        $user = auth()->guard('api')->user();

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized: token tidak valid atau tidak dikirim"
            ], 401);
        }

        $borrowing=Borrowing::with('book', 'user')->get();

        if($borrowing->isEmpty()){
            return response()->json([
                "success" => false,
                "messege" => "resource data not found !"
            ], 401);
        }


        return response()->json([
            "success" => true,
            "messege" => "Get all resource",
            "data" => $borrowing
        ], 200);
    }

    public function store(Request $request){
        //1. validator
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
        ]);
        //2. check validator eror
        if ($validator->fails()){
            return response()->json([
                "success"=> false,
                "messege" => $validator->errors()
            ], 422);
        };

        // Ambil data buku
        $book = Book::findOrFail($request->book_id);

        // Cek stok
        if ($book->available_stock < $request->lostOfBook) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Tersedia hanya ' . $book->available_stock . ' buku.'
            ], 400);
        }

        // Kurangi stok buku
        $book->available_stock -= $request->lostOfBook;
        $book->save();


        //insert data
        $borrowing = Borrowing::create([
            "user_id" => $request->user_id,
            "book_id" => $request->book_id,
            "lostOfBook" => 1,
            "borrowing_date" => now(),
            "status" => "dipinjam"
        ]);

        return response()->json([
            "success" => true,
            "messege" => "borrowing success",
            "data" => $borrowing
        ], 201);
    }

    //show
    public function show(string $id){
        $borrowing = Borrowing::find($id);

        if(!$borrowing){
            return response()->json([
                "success"=>false,
                "messege" => "resource not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "messege" => "Get resource",
            "data" => $borrowing
        ]);
    }

    //update
    public function update(Request $request, string $id){
        //1, cari data
        $borrowing = Borrowing::find($id);
        if(!$borrowing){
            return response()->json([
                "success"=>false,
                "messege" => "resource not found"
            ], 404);
        }
        //2. validator
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id'
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
            "book_id" => $request->book_id,
        ];

        //4, update data
        $borrowing->update($data);
        return response()->json([
            "success" => true,
            "messege" => "resource updated",
            "data" => $borrowing
        ], 200);
    }
     //delete
    public function destroy(string $id){
        $borrowing = Borrowing::find($id);
        if(!$borrowing){
            return response()->json([
                "success"=>false,
                "messege" => "resourse not found"
            ], 404);
        }
        $borrowing->delete();
        return response() ->json([
            "success" =>true,
            "messege" => "resource deleted",
            "data" => $borrowing
        ], 200);
    }
}
