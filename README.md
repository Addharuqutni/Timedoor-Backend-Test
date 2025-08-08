# Timedoor Backend Test

Aplikasi backend Laravel untuk mengelola sistem perpustakaan digital dengan fitur rating buku dan analisis penulis terbaik.

## Deskripsi Proyek

Sistem ini adalah aplikasi manajemen perpustakaan yang memungkinkan pengguna untuk:
- Melihat daftar buku dengan sistem pencarian dan pagination
- Memberikan rating untuk buku (skala 1-10)
- Melihat top 10 penulis berdasarkan rating tinggi (>5)
- Mengelola data buku, penulis, dan kategori buku

## Teknologi yang Digunakan

- **Laravel 12.x** - Framework PHP untuk backend
- **PHP 8.2+** - Bahasa pemrograman utama
- **Mysql** - Database untuk penyimpanan data
- **Blade Templates** - Template engine untuk frontend
- **Eloquent ORM** - Object-Relational Mapping
- **Laravel Factories & Seeders** - Untuk generate data dummy

## Struktur Database

### Tabel `authors`
- `id` (Primary Key)
- `name` (String) - Nama penulis
- `created_at`, `updated_at`

### Tabel `book_categories`
- `id` (Primary Key)  
- `name` (String) - Nama kategori buku
- `created_at`, `updated_at`

### Tabel `books`
- `id` (Primary Key)
- `title` (String) - Judul buku
- `author_id` (Foreign Key) - Referensi ke tabel authors
- `book_category_id` (Foreign Key) - Referensi ke tabel book_categories
- `created_at`, `updated_at`

### Tabel `ratings`
- `id` (Primary Key)
- `book_id` (Foreign Key) - Referensi ke tabel books
- `rating` (TinyInteger) - Rating 1-10
- `created_at`, `updated_at`

## Fitur Utama

### 1. Daftar Buku (Homepage)
- **Route**: `GET /`
- **Controller**: `BookController@index`
- **Fitur**:
  - Pencarian buku berdasarkan judul atau nama penulis
  - Pagination dengan opsi 10-100 item per halaman
  - Menampilkan rata-rata rating dan jumlah rating per buku
  - Sorting berdasarkan rating tertinggi

### 2. Top 10 Penulis
- **Route**: `GET /authors/top`
- **Controller**: `AuthorController@top`
- **Fitur**:
  - Menampilkan 10 penulis dengan rating terbanyak > 5
  - Menghitung jumlah voter untuk setiap penulis

### 3. Sistem Rating
- **Route**: 
  - `GET /ratings/create` - Form rating
  - `POST /ratings` - Submit rating
- **Controller**: `RatingController`
- **Fitur**:
  - Form untuk memilih penulis dan buku
  - Validasi rating 1-10
  - Validasi bahwa buku harus sesuai dengan penulis yang dipilih

## Data Seeding

Sistem menggunakan factories untuk generate data dummy:
- **1,000 Authors** - Penulis dengan nama random
- **3,000 Book Categories** - Kategori buku
- **100,000 Books** - Buku dengan judul random, author dan kategori random
- **500,000 Ratings** - Rating random 1-10 untuk buku

Data di-seed dalam chunks untuk menghindari memory issues:
- Books: 10,000 per batch
- Ratings: 50,000 per batch

## Penggunaan

### 1. Melihat Daftar Buku
- Akses homepage di `http://localhost:8000`
- Gunakan search box untuk mencari buku atau penulis
- Pilih jumlah item per halaman (10-100)
- Buku diurutkan berdasarkan rating tertinggi

### 2. Melihat Top Authors
- Akses `http://localhost:8000/authors/top`
- Melihat 10 penulis dengan jumlah rating > 5 terbanyak

### 3. Memberikan Rating
- Akses `http://localhost:8000/ratings/create`
- Pilih penulis dari dropdown
- Pilih buku dari penulis tersebut
- Berikan rating 1-10
- Submit form

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Homepage - Daftar buku dengan pagination dan search |
| GET | `/authors/top` | Top 10 penulis berdasarkan rating |
| GET | `/ratings/create` | Form untuk memberikan rating |
| POST | `/ratings` | Submit rating baru |

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

**Addharuqutni**
- GitHub: [@Addharuqutni](https://github.com/Addharuqutni)
- Repository: [Timedoor-Backend-Test](https://github.com/Addharuqutni/Timedoor-Backend-Test)

