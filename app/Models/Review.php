<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Invalidate Cache on Review Update
    public static function booted()
    {
        static::updated(function (Review $review) {
            Cache::forget('book:' . $review->book_id);
        });

        static::deleted(function (Review $review) {
            Cache::forget('book:' . $review->book_id);
        });
    }
}
