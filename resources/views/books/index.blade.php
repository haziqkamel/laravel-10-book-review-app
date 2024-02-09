@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>

<form method="GET" action="{{route('books.index')}}" class="mb-4 flex items-center gap-3">
    @csrf
    <input class="input" type="text" name="title" placeholder="Search by title" value="{{request('title')}}">
    <button type="submit" class="btn">Search</button>
    <a href="{{route('books.index')}}" class="btn">Clear</a>
</form>

<ul>
    @forelse ($books as $book)
    <li class="mb-4">
        <div class="book-item">
            <div class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                    <a href="{{route('books.show', $book)}}" class="book-title">{{$book->title}}</a>
                    <span class="book-author">by {{$book->author}}</span>
                </div>
                <div>
                    <div class="book-rating">
                        {{number_format($book->reviews_avg_rating, 1)}}
                    </div>
                    <div class="book-review-count">
                        from {{$book->reviews_count ?? 0}} {{Str::plural('review', $book->reviews_count ?? 1)}}
                    </div>
                </div>
            </div>
        </div>
    </li>
    @empty
    <li class="mb-4">
        <div class="empty-book-item">
            <p class="empty-text">No books found</p>
            <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
        </div>
    </li>
    @endforelse
</ul>
@endsection