<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Dropdown options for "List shown"
        $perPageOptions = range(10, 100, 10);

        // Get per page from request or default to 10
        $perPage = $request->input('per_page', 10);

        // Search filter
        $search = $request->input('search');

        $books = Book::with(['author', 'bookCategory'])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhereHas('author', fn ($q2) => $q2->where('name', 'like', "%$search%"));
                });
            })
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('ratings_count')
            ->take($perPage)
            ->get();

        return view('books.index', compact('books', 'perPageOptions', 'perPage', 'search'));
    }
}
