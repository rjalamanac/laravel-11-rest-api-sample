<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/products',ProductController::class);
// Display all books
Route::get('/books', [BookController::class, 'index']);

// Display a single book
Route::get('/books/{id}', [BookController::class, 'show']);

// Add a new book
Route::post('/books', [BookController::class, 'store']);

// Update an existing book
Route::put('/books/{id}', [BookController::class, 'update']);

// Delete a book
Route::delete('/books/{id}', [BookController::class, 'destroy']);