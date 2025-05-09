@extends('layout.dash') {{-- or public layout if you have another one --}}

@section('konten')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid mb-4 rounded">
            @endif

            <h1 class="mb-3">{{ $news->title }}</h1>

            <div class="text-muted mb-4">
                By {{ $news->user->name ?? 'Anonymous' }} • Category: {{ $news->kategori->name ?? '-' }}
            </div>

            <div style="font-size: 1.1rem;">
                {!! nl2br(e($news->content)) !!}
            </div>

            @if($relatedNews->count())
            <div class="mt-5">
                <h5>Related News</h5>
                <ul class="list-unstyled">
                    @foreach($relatedNews as $item)
                    <li class="mb-2">
                        <a href="{{ url('/news/' . $item->slug) }}" class="text-decoration-none">
                            {{ $item->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
