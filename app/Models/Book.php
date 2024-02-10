<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Local Query Scope Builder 

    // Title Scope
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount(['reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)]);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->withReviewsCount()
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg(['reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)], 'rating');
    }

    // Highest Rated Scope
    public function scopeHighestRated(Builder $query): Builder
    {
        return $query->withAvgRating()
            ->orderBy('reviews_avg_rating', 'desc');
    }

    // Min Amount of Reviews
    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }

    public static function booted()
    {
        static::updated(function (Book $book) {
            Cache::forget('book:' . $book->id);
        });

        static::deleted(function (Book $book) {
            Cache::forget('book:' . $book->id);
        });
    }
}
