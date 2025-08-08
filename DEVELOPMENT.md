# Development Guide

## Overview
Panduan pengembangan untuk project Timedoor Backend Test.

## Project Structure

```
timedoor-backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── BookController.php      # Handle books listing & search
│   │       ├── AuthorController.php    # Handle top authors
│   │       └── RatingController.php    # Handle rating submission
│   ├── Models/
│   │   ├── Author.php                  # Author model with relationships
│   │   ├── Book.php                    # Book model with relationships  
│   │   ├── BookCategory.php            # Book category model
│   │   ├── Rating.php                  # Rating model
│   │   └── User.php                    # Default Laravel user model
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── factories/                      # Model factories for seeding
│   ├── migrations/                     # Database schema migrations
│   ├── seeders/                        # Database seeders
│   └── database.sqlite                 # SQLite database file
├── resources/
│   └── views/                          # Blade templates
│       ├── books/
│       ├── authors/
│       └── ratings/
├── routes/
│   └── web.php                         # Web routes definition
└── tests/                              # Test files
```

## Development Environment Setup

### Local Development
```bash
# Clone repository
git clone https://github.com/Addharuqutni/Timedoor-Backend-Test.git
cd timedoor-backend

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Code Quality Tools

#### Laravel Pint (Code Formatting)
```bash
# Format code according to Laravel standards
./vendor/bin/pint

# Check what would be fixed without making changes
./vendor/bin/pint --test
```

#### Testing
```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/ExampleTest.php
```

## Database Development

### Migrations
```bash
# Create new migration
php artisan make:migration create_new_table --create=new_table

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh database
php artisan migrate:fresh
```

### Seeders & Factories
```bash
# Create new seeder
php artisan make:seeder NewTableSeeder

# Create new factory
php artisan make:factory NewModelFactory --model=NewModel

# Run specific seeder
php artisan db:seed --class=NewTableSeeder
```

### Model Creation
```bash
# Create model with migration, factory, and seeder
php artisan make:model NewModel -mfs

# Create model with controller
php artisan make:model NewModel -mc
```

## Controller Development

### Best Practices

1. **Single Responsibility**: Setiap controller method harus fokus pada satu tugas
2. **Validation**: Selalu validasi input menggunakan Form Request atau manual validation
3. **Eloquent Relationships**: Gunakan eager loading untuk menghindari N+1 queries

#### Example Controller Structure
```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Validation
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        
        // Query with eager loading
        $books = Book::with(['author', 'bookCategory'])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhereHas('author', fn ($q2) => 
                            $q2->where('name', 'like', "%$search%"));
                });
            })
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('ratings_count')
            ->take($perPage)
            ->get();
            
        return view('books.index', compact('books', 'perPage', 'search'));
    }
}
```

## Model Development

### Eloquent Relationships

#### Defining Relationships
```php
// Author Model
class Author extends Model
{
    protected $fillable = ['name'];
    
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

// Book Model  
class Book extends Model
{
    protected $fillable = ['title', 'author_id', 'book_category_id'];
    
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    
    public function bookCategory()
    {
        return $this->belongsTo(BookCategory::class);
    }
    
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
```

#### Query Optimization
```php
// Good: Eager loading to prevent N+1 queries
$books = Book::with(['author', 'bookCategory'])->get();

// Good: Aggregate functions
$books = Book::withCount('ratings')
    ->withAvg('ratings', 'rating')
    ->get();

// Bad: N+1 query problem
$books = Book::all();
foreach ($books as $book) {
    echo $book->author->name; // This creates N additional queries
}
```

## Frontend Development (Blade Templates)

### Template Structure
```php
// resources/views/layouts/app.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Timedoor Backend')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>

// resources/views/books/index.blade.php
@extends('layouts.app')

@section('title', 'Books List')

@section('content')
    <h1>Books</h1>
    
    @foreach($books as $book)
        <div class="book-item">
            <h3>{{ $book->title }}</h3>
            <p>Author: {{ $book->author->name }}</p>
            <p>Category: {{ $book->bookCategory->name }}</p>
            <p>Rating: {{ number_format($book->ratings_avg_rating, 1) }} 
               ({{ $book->ratings_count }} reviews)</p>
        </div>
    @endforeach
@endsection
```

## Testing

### Feature Tests
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_books_index_displays_books()
    {
        // Arrange
        $author = Author::factory()->create();
        $category = BookCategory::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'book_category_id' => $category->id,
        ]);
        
        // Act
        $response = $this->get('/');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee($book->title);
        $response->assertSee($author->name);
    }
    
    public function test_books_search_functionality()
    {
        // Arrange
        $author = Author::factory()->create(['name' => 'Test Author']);
        $book = Book::factory()->create([
            'title' => 'Test Book',
            'author_id' => $author->id,
        ]);
        
        // Act
        $response = $this->get('/?search=Test');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('Test Book');
    }
}
```

### Unit Tests
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookModelTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_book_belongs_to_author()
    {
        // Arrange
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);
        
        // Act & Assert
        $this->assertInstanceOf(Author::class, $book->author);
        $this->assertEquals($author->id, $book->author->id);
    }
}
```

## Performance Optimization

### Database Optimization

1. **Indexes**: Tambahkan index pada kolom yang sering di-query
```php
// In migration
Schema::table('books', function (Blueprint $table) {
    $table->index('title');
    $table->index(['author_id', 'book_category_id']);
});
```

2. **Query Optimization**: Gunakan select() untuk membatasi kolom yang diambil
```php
$books = Book::select('id', 'title', 'author_id')
    ->with(['author:id,name'])
    ->get();
```

3. **Pagination**: Selalu gunakan pagination untuk large datasets
```php
$books = Book::paginate(20);
```

### Caching Strategies
```php
// Cache expensive queries
$topAuthors = Cache::remember('top_authors', 3600, function () {
    return Author::select('authors.id', 'authors.name')
        ->join('books', 'books.author_id', '=', 'authors.id')
        ->join('ratings', 'ratings.book_id', '=', 'books.id')
        ->where('ratings.rating', '>', 5)
        ->groupBy('authors.id', 'authors.name')
        ->selectRaw('COUNT(ratings.id) as voter_count')
        ->orderByDesc('voter_count')
        ->limit(10)
        ->get();
});
```

## Debugging

### Laravel Debugbar (Development)
```bash
# Install Laravel Debugbar
composer require barryvdh/laravel-debugbar --dev
```

### Logging
```php
// In controller
use Illuminate\Support\Facades\Log;

Log::info('Book search performed', [
    'search_term' => $request->input('search'),
    'results_count' => $books->count()
]);
```

### Database Query Debugging
```php
// Enable query logging
DB::enableQueryLog();

// Your queries here
$books = Book::with('author')->get();

// Get executed queries
$queries = DB::getQueryLog();
dd($queries);
```

## Deployment

### Production Checklist

1. **Environment Configuration**
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Set proper database configuration
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/production/database.sqlite
```

2. **Optimization Commands**
```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force
```

3. **File Permissions**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## Git Workflow

### Branch Strategy
```bash
# Feature development
git checkout -b feature/new-feature
git commit -am "Add new feature"
git push origin feature/new-feature

# Create Pull Request for code review

# After merge, cleanup
git checkout main
git pull origin main
git branch -d feature/new-feature
```

### Commit Message Convention
```
feat: add book search functionality
fix: resolve N+1 query in books listing
docs: update API documentation
refactor: optimize database queries
test: add unit tests for Book model
```

## Code Review Guidelines

1. **Security**: Check for SQL injection, XSS vulnerabilities
2. **Performance**: Review query efficiency, N+1 problems
3. **Code Quality**: Follow PSR standards, proper naming conventions
4. **Testing**: Ensure adequate test coverage
5. **Documentation**: Update docs for new features

## Common Issues & Solutions

### Memory Issues During Seeding
```php
// Solution: Use chunking
$bookCount = 100000;
$chunkSize = 10000;

for ($i = 0; $i < $bookCount / $chunkSize; $i++) {
    Book::factory($chunkSize)->create();
    
    // Clear memory
    if ($i % 5 === 0) {
        gc_collect_cycles();
    }
}
```

### N+1 Query Problems
```php
// Problem
$books = Book::all();
foreach ($books as $book) {
    echo $book->author->name; // N+1 queries
}

// Solution
$books = Book::with('author')->get();
foreach ($books as $book) {
    echo $book->author->name; // Single query with joins
}
```

### Database Locking Issues
```php
// Use database transactions for related operations
DB::transaction(function () {
    $book = Book::create($bookData);
    Rating::create(['book_id' => $book->id, 'rating' => 5]);
});
```
