<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $books = Book::with('author')->paginate($perPage, ['*'], 'page', $page);

        return response()->json($books);
    }

    public function show($id)
    {
        $book = Book::with('author')->findOrFail($id);

        return response()->json($book);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
        ];

        $this->validate($request, $rules);

        $book = Book::create([
            'title' => $request->input('title'),
            'author_id' => $request->input('author_id'),
            'user_id' => $request->user()->id,
        ]);

        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($id);
        $book->title = $request->input('title', $book->title);
        $book->author_id = $request->input('author_id', $book->author_id);

        $book->save();

        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        $book->delete();

        return response()->json($book);
    }
}
