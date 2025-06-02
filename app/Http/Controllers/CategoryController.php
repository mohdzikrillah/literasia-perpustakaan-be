<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index() {
        $catogory = Category::all();

        if($catogory->isEmpty()){
            return response()->json([
                "success"=>true,
                "messege" => "resource data not found"
            ], 200);
        }

        return response()->json([
            "success"=> true,
            "messege" => "Get all resource",
            "data" => $catogory
        ], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:255",
            "description" => "required|string|max:455"
        ]);

        if($validator->fails()){
            return response()->json([
                "success"=>false,
                "messege" => $validator->errors()
            ], 400);
        }
        $category = Category::create([
            "name" => $request->name,
            "description" => $request->description
            
        ]);

        return response()->json([
            "success" => true,
            "messege" => "resource created",
            "data" => $category,
        ], 201);
    }

    //show
    public function show(string $id){
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "success"=>false,
                "messege" => "resource not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "messege" => "Get resource",
            "data" => $category
        ]);
    }

    //update
    public function update(Request $request, string $id){
        //1, cari data
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                "success"=>false,
                "messege" => "resource not found"
            ], 404);
        }
        //2. validator
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:225",
            "description" => "required|string|max:455"
        ]);

        if($validator->fails()){
            return response()->json([
                "success"=>false,
                "messege" => $validator->errors()
            ], 400);
        }
        //3 siapkan data yang mau diupdate
        $data = [
            "name" => $request->name,
            "description" => $request->description
        ];

        //4, update data
        $category->update($data);
        return response()->json([
            "success" => true,
            "messege" => "resource updated",
            "data" => $category
        ], 200);
    }
     //delete
     public function destroy(string $id){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                "success"=>false,
                "messege" => "resourse not found"
            ], 404);
        }
        $category->delete();
        return response() ->json([
            "success" =>true,
            "messege" => "resource deleted",
            "data" => $category
        ], 200);
     }
}
