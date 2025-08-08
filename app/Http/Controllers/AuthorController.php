<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function top()
    {
        // Get top 10 authors by count of ratings > 5
        $authors = Author::select('authors.id', 'authors.name')
            ->join('books', 'books.author_id', '=', 'authors.id')
            ->join('ratings', 'ratings.book_id', '=', 'books.id')
            ->where('ratings.rating', '>', 5)
            ->groupBy('authors.id', 'authors.name')
            ->selectRaw('COUNT(ratings.id) as voter_count')
            ->orderByDesc('voter_count')
            ->limit(10)
            ->get();

        return view('authors.top', compact('authors'));
    }
}
