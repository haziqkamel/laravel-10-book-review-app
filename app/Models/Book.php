<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Popular Scope
    // Get the most popular book with most reviews count
    public function scopePopular(Builder $query): Builder
    {
        return $query->withCount('reviews')->orderBy('reviews_count', 'desc');
    }

    // Highest Rated Scope
    public function scopeHighestRated(Builder $query): Builder
    {
        return $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
    }
}
