# API Documentation

## Overview
Dokumentasi lengkap untuk Timedoor Backend Test API endpoints.

## Base URL
```
http://localhost:8000
```

## Authentication
Aplikasi ini belum menggunakan authentication system.

---

## Endpoints

### 1. Get Books List
Mengambil daftar buku dengan pagination, search, dan rating information.

**Endpoint:** `GET /`

**Parameters:**
- `search` (optional) - String untuk pencarian judul buku atau nama penulis
- `per_page` (optional) - Jumlah item per halaman (10-100, default: 10)

**Example Request:**
```
GET /?search=harry&per_page=20
```

**Response:**
- HTML page dengan daftar buku
- Menampilkan: judul, penulis, kategori, rata-rata rating, jumlah rating

**Features:**
- Search berdasarkan judul buku atau nama penulis
- Pagination dengan opsi 10-100 items per page
- Sorting berdasarkan rata-rata rating (descending)
- Menampilkan jumlah total rating per buku

---

### 2. Get Top Authors
Mengambil 10 penulis terbaik berdasarkan jumlah rating > 5.

**Endpoint:** `GET /authors/top`

**Parameters:** None

**Example Request:**
```
GET /authors/top
```

**Response:**
- HTML page dengan top 10 authors
- Menampilkan: nama penulis, jumlah voter (rating > 5)

**Query Logic:**
```sql
SELECT authors.id, authors.name, COUNT(ratings.id) as voter_count
FROM authors
JOIN books ON books.author_id = authors.id  
JOIN ratings ON ratings.book_id = books.id
WHERE ratings.rating > 5
GROUP BY authors.id, authors.name
ORDER BY voter_count DESC
LIMIT 10
```

---

### 3. Create Rating Form
Menampilkan form untuk memberikan rating pada buku.

**Endpoint:** `GET /ratings/create`

**Parameters:** None

**Example Request:**
```
GET /ratings/create
```

**Response:**
- HTML form dengan dropdown authors dan books
- Form fields: author_id, book_id, rating

---

### 4. Submit Rating
Menyimpan rating baru ke database.

**Endpoint:** `POST /ratings`

**Content-Type:** `application/x-www-form-urlencoded` atau `multipart/form-data`

**Parameters:**
- `author_id` (required) - ID penulis (integer)
- `book_id` (required) - ID buku (integer, harus sesuai dengan author_id)
- `rating` (required) - Rating 1-10 (integer)

**Validation Rules:**
```php
'author_id' => 'required|exists:authors,id',
'book_id' => [
    'required',
    Rule::exists('books', 'id')->where('author_id', $request->author_id),
],
'rating' => 'required|integer|min:1|max:10',
```

**Example Request:**
```
POST /ratings
Content-Type: application/x-www-form-urlencoded

author_id=1&book_id=5&rating=8
```

**Success Response:**
- Redirect ke homepage (`/`) dengan flash message "Rating submitted!"

**Error Response:**
- Redirect back dengan validation errors

---

## Database Schema

### Authors Table
```sql
CREATE TABLE authors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Book Categories Table
```sql
CREATE TABLE book_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Books Table
```sql
CREATE TABLE books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    author_id INTEGER NOT NULL,
    book_category_id INTEGER NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
    FOREIGN KEY (book_category_id) REFERENCES book_categories(id) ON DELETE CASCADE
);
```

### Ratings Table
```sql
CREATE TABLE ratings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    book_id INTEGER NOT NULL,
    rating TINYINT NOT NULL, -- 1-10
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);
```

---

## Sample Data

### Sample Author
```json
{
    "id": 1,
    "name": "J.K. Rowling",
    "created_at": "2025-08-08T10:00:00.000000Z",
    "updated_at": "2025-08-08T10:00:00.000000Z"
}
```

### Sample Book
```json
{
    "id": 1,
    "title": "Harry Potter and the Philosopher's Stone",
    "author_id": 1,
    "book_category_id": 1,
    "created_at": "2025-08-08T10:00:00.000000Z",
    "updated_at": "2025-08-08T10:00:00.000000Z",
    "author": {
        "id": 1,
        "name": "J.K. Rowling"
    },
    "book_category": {
        "id": 1,
        "name": "Fantasy"
    },
    "ratings_count": 150,
    "ratings_avg_rating": 8.5
}
```

### Sample Rating
```json
{
    "id": 1,
    "book_id": 1,
    "rating": 9,
    "created_at": "2025-08-08T10:00:00.000000Z",
    "updated_at": "2025-08-08T10:00:00.000000Z"
}
```

---

## Error Handling

### Validation Errors
Ketika data yang dikirim tidak valid, aplikasi akan redirect back dengan error messages.

**Common Validation Errors:**
- `author_id` tidak ada di database
- `book_id` tidak sesuai dengan `author_id` yang dipilih
- `rating` bukan integer atau di luar range 1-10

### Database Errors
- Foreign key constraint violations
- Database connection errors

### 404 Errors
- Route tidak ditemukan
- Resource tidak ada

---

## Performance Notes

1. **Database Queries Optimization:**
   - Menggunakan eager loading (`with()`) untuk menghindari N+1 query problem
   - Index pada foreign keys sudah otomatis dibuat oleh Laravel migrations

2. **Pagination:**
   - Semua listing menggunakan pagination untuk handle large datasets
   - Default 10 items per page, max 100 items per page

3. **Seeding Performance:**
   - Data seeding dilakukan dalam chunks untuk menghindari memory issues
   - Books: 10,000 per batch
   - Ratings: 50,000 per batch

---

## Future Enhancements

1. **REST API JSON Responses:**
   - Convert endpoints ke JSON API format
   - Add proper HTTP status codes
   - Implement API versioning

2. **Authentication:**
   - Add user authentication system
   - Implement API tokens

3. **Advanced Features:**
   - Book reviews (text comments)
   - User favorites
   - Recommendation system
   - Advanced search filters

4. **Performance:**
   - Add database indexes for search optimization
   - Implement caching for frequently accessed data
   - Add rate limiting
