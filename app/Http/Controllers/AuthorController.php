<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function showAllAuthors()
    {
        return response()->json(Author::all());
    }

    public function showOneAuthor($id)
    {
        return response()->json(Author::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:authors',
            'github' => 'nullable|string|max:100',
            'twitter' => 'nullable|string|max:100',
            'location' => 'required|alpha_dash|min:2|max:100',
            'latest_article_published' => 'nullable|string|max:255'
        ]);

        $author = Author::create($request->all());

        return response()->json($author, 201);
    }

    public function update($id, Request $request)
    {
        $author = Author::findOrFail($id);
        $this->validate($request, [
            'name'  => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:authors,email,' . $author->id,
            'github' => 'nullable|string|max:100',
            'twitter' => 'nullable|string|max:100',
            'location' => 'sometimes|required|alpha_dash|max:100',
            'latest_article_published' => 'nullable|string|max:255'
        ]);
        $author->update($request->all());

        return response()->json($author, 200);
    }

    public function delete($id)
    {
        Author::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
