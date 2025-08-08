<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'book_category_id',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'book_id');
    }

    public function bookCategory()
    {
        return $this->belongsTo(BookCategory::class);
    }
}
