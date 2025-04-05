@extends('layout.dash')

@section('konten')
<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .img-container {
        overflow: hidden;
        height: 220px;
    }

    .user-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease-in-out;
    }

    .hover-card:hover .user-image {
        transform: scale(1.1);
    }
</style>
<div class="py-4">
    <div class="row g-4">
        @foreach ($users as $user)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('user.detail', $user->id) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card position-relative">
                        <div class="img-container">
                            <img src="{{ asset('storage/' . $user->gambar) }}" 
                                 class="card-img-top user-image" 
                                 alt="{{ $user->name }}">
                        </div>
                        <div class="card-body p-3 bg-white">
                            <h5 class="card-title mb-1 text-truncate fw-semibold">{{ $user->name }}</h5>
                            <p class="card-text text-muted small mb-0 text-truncate">
                                {{ $user->alamat ?? 'Alamat tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection


