<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $authors = Author::with('books')->paginate($perPage, ['*'], 'page', $page);

        return response()->json($authors);
    }

    public function show($id)
    {
        $author = Author::with('books')->findOrFail($id);

        return response()->json($author);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ];

        $this->validate($request, $rules);

        $author = Author::create([
            'name' => $request->input('name'),
            'birth_date' => $request->input('birth_date'),
            'user_id' => $request->user()->id,
        ]);

        return response()->json($author);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($id);
        $author->name = $request->input('name', $author->name);
        $author->birth_date = $request->input('birth_date', $author->birth_date);

        $author->save();

        return response()->json($author);
    }

    public function destroy(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        if ($author->books->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete author with books',
            ], 422);
        }

        if ($author->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $author->delete();

        return response()->json(null, 204);
    }
}
