<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Author;
use App\Models\BookCategory;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Author::factory(1000)->create();
        BookCategory::factory(3000)->create();

        // To avoid memory issues, seed books in chunks
        $bookCount = 100000;
        $chunk = 10000;
        for ($i = 0; $i < $bookCount / $chunk; $i++) {
            Book::factory($chunk)->create();
        }

        $ratingCount = 500000;
        $chunk = 50000;
        for ($i = 0; $i < $ratingCount / $chunk; $i++) {
            Rating::factory($chunk)->create();
        }
    }
}
