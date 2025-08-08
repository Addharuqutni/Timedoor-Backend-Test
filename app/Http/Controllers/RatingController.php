<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Rating;
use App\Models\Author;
use Illuminate\Validation\Rule;

class RatingController extends Controller
{
    public function create()
    {
        $authors = Author::with('books')->get();
        return view('ratings.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:authors,id',
            'book_id' => [
                'required',
                Rule::exists('books', 'id')->where('author_id', $request->author_id),
            ],
            'rating' => 'required|integer|min:1|max:10',
        ]);

        Rating::create([
            'book_id' => $request->book_id,
            'rating' => $request->rating,
        ]);

        return redirect()->route('books.index')->with('success', 'Rating submitted!');
    }
}
