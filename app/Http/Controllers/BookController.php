<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index() {
        $books = Book::with('category','author')->get();
        // ::with('genre','author')->get();

        if ($books->isEmpty()){
            return response()->json([
                "success"=> true,
                "messege" => "resource data not found"
            ], 200);
        }
        return response()->json([
            "success"=> true,
            "messege" => "Get all resource",
            "data" => $books,
        ], 200);
    }
    public function store(Request $request){
        //1 validator
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string|max:1000',
            'book_cover' => 'required|image|mimes:jpeg.png,jpg|max:2048',
            'author_id' => 'required|integer|exists:authors,id',
            'category_id' => 'required|integer|exists:categories,id',
            'available_stock' => 'required|numeric|min:0',
        ]);

        //2. check validator eror
        if ($validator->fails()){
            return response()->json([
                "success"=> false,
                "message" => $validator->errors()
            ], 422);
        };
        //3. upload image
        $image = $request->file("book_cover");
        $image ->store('books', 'public');

        //4. insert data
        $book = Book::create([
            "title" => $request->title,
            "synopsis" => $request->synopsis,
            "book_cover" => $image->hashName(),
            "author_id" => $request->author_id,
            "category_id" => $request->category_id,
            "available_stock" => $request->available_stock,
        ]);

        //5. response
        return response()->json([
            "success"=> true,
            "message" => "resource add successfully!",
            "data" => $book
        ],201);
    }

    //show
    public function show(string $id){
        $book = Book::with('category','author')-> find($id);

        if (!$book){
            return response()->json([
                "success" => false,
                "message" => "resource not found",
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "get detail resource",
            "data" => $book
        ]);
    }

    //update
    public function update(string $id, Request $request){
        //mencari data
        $book = Book::find($id);
        if (! $book){
            return response()->json([
                "success" => false,
                "message" => "resourse not found"
            ], 404);
        }
        // 2 validator
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string|max:1000',
            'book_cover' => 'required|image|mimes:jpeg.png,jpg|max:2048',
            'author_id' => 'required|integer|exists:authors,id',
            'category_id' => 'required|integer|exists:categories,id',
            'available_stock' => 'required|numeric|min:0'
        ]);
        if ($validator->fails()) {
            return response()->json([
            "success" => false,
            "message" => $validator->errors()
        ], 422);
        }

        //3 siapkan data yang mau diupdate
        $data= [
            "title" => $request->title,
            "synopsis" => $request->synopsis,
            "author_id" => $request->author_id,
            "category_id" => $request->category_id,
            "available_stock" => $request->available_stock,
        ];
        //4 handle image(uapload atau delete)
        if ($request->book_cover){
            $image = $request->file('book_cover');
            $image->store('books', 'public');


            if($book->book_cover){
                Storage::disk('public')->delete('books/'.$book->book_cover);
            }
            $data['book_cover'] =$image->hashName();
        }

        //5. update data
        $book->update($data);
        return response()->json([
            "success" => true,
            "message" => "resourse updated successfully",
            "data" => $book
        ]);
    }

    //delete
    public function destroy(string $id){
        $book = Book::find($id);
        if (!$book){
            return response()->json([
                "success" => true,
                "messege" => "resourse not found",
            ]);
        }
        if ($book ->book_cover){
            Storage::disk('public')->delete('books/'.$book->book_cover);
        }

        $book ->delete();
        return response()->json([
            "success" => true,
            "messege" => "resourse deleted successfully",
        ]);
        }
}

