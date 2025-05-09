
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-8">Latest News</h1>

    <div class="space-y-8">
        @foreach($news as $item)
        <div class="border-b pb-6">
            <a href="{{ url('/news/' . Str::slug($item->title)) }}" class="block">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-60 object-cover rounded mb-4" alt="{{ $item->title }}">
                @endif
                <h2 class="text-2xl font-semibold text-gray-900">{{ $item->title }}</h2>
                <div class="text-gray-600 text-sm mb-2">
                    by {{ $item->user->name ?? 'Unknown' }} | {{ $item->kategori->name ?? 'No Category' }}
                </div>
                <p class="text-gray-700 line-clamp-3">
                    {{ Str::limit(strip_tags($item->content), 150) }}
                </p>
            </a>
        </div>
        @endforeach
    </div>
</div>
