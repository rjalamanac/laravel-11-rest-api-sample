<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

/**
  * @OA\Tag(
  *     name="Books",
  *     description="API Endpoints of Products"
  * )
  */

class BookController extends Controller
{
      /**
  * @OA\Get(
  *     path="/books",
  *     tags={"Books"},
  *     summary="Get list of books",
  *     @OA\Response(
  *         response=200,
  *         description="A list of books"
  *     )
  * )
  */
    public function index()
    {
        return Book::all();
    }

    // Display a single book
    public function show($id)
    {
        return Book::find($id);
    }

    // Add a new book
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        return Book::create($jalidated);
    }

    // Update an existing book
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'title' => 'string|max:255',
            'author' => 'string|max:255',
        ]);

        $book->update($validated);

        return $book;
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}