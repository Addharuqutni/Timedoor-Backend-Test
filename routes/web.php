<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\RatingController;

Route::get('/', [BookController::class, 'index'])->name('books.index');
Route::get('/authors/top', [AuthorController::class, 'top'])->name('authors.top');
Route::get('/ratings/create', [RatingController::class, 'create'])->name('ratings.create');
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
