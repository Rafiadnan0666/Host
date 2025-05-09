@extends('layout.dash')

@section('konten')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-4xl font-bold mb-4">{{ $news->title }}</h1>

    <div class="flex items-center space-x-4 mb-6">
        <div class="text-gray-600 text-sm">
            by <span class="font-semibold">{{ $news->user->name ?? 'Unknown' }}</span>
            • Category: <span class="font-semibold">{{ $news->kategori->name ?? 'No Category' }}</span>
            • {{ $news->created_at->format('M d, Y') }}
        </div>
    </div>

    @if($news->image)
        <img src="{{ asset('storage/' . $news->image) }}" class="w-full rounded-lg mb-6" alt="{{ $news->title }}">
    @endif

    <div class="prose max-w-none mb-10">
        {!! nl2br(e($news->content)) !!}
    </div>

    <hr class="my-10">

    @if($related->count())
    <h2 class="text-2xl font-bold mb-6">More from {{ $news->kategori->name }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($related as $item)
        <a href="{{ url('/news/' . Str::slug($item->title)) }}" class="block">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="h-48 w-full object-cover rounded mb-2" alt="{{ $item->title }}">
            @endif
            <h3 class="text-lg font-semibold text-gray-800">{{ $item->title }}</h3>
            <p class="text-gray-600 text-sm line-clamp-2">
                {{ Str::limit(strip_tags($item->content), 100) }}
            </p>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection
