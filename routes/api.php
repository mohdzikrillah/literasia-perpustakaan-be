<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReturnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function(){
    Route::apiResource('/books', BookController::class)->only(['show','index']);
    Route::apiResource('/categories', CategoryController::class)->only(['show', 'index']);
    Route::apiResource('/authors', AuthorController::class)->only(['show', 'index']);
    Route::apiResource('/borrowings', BorrowingController::class)->only('index','store');
    Route::apiResource('/returns', ReturnController::class)->only('index','store');

    Route::middleware(['role:admin'])->group(function(){
        Route::apiResource('/books', BookController::class)->only(['destroy', 'store','update']);
        Route::apiResource('/categories', CategoryController::class)->only(['destroy','store','update']);
        Route::apiResource('/authors', AuthorController::class)->only(['destroy','show','store','update']);
        Route::apiResource('/borrowings', BorrowingController::class)->only(['show','update', 'destroy']);
        Route::apiResource('/returns', ReturnController::class)->only([ 'show','update','destroy']);
        Route::apiResource('/users', AuthController::class)->only([ 'index','show','store','update','destroy']);

    });
});
