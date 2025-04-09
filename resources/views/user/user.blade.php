@extends('layout.dash')

@section('konten')
<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .img-container {
        overflow: hidden;
        height: 180px;
        background-color: #f9f9f9;
    }

    .user-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease-in-out;
    }

    .hover-card:hover .user-image {
        transform: scale(1.08);
    }
</style>

<div class=" py-4">
    <!-- 🔍 Search & Filter -->
    <div class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" id="searchInput" class="form-control" placeholder="Search name or email...">
        </div>
        <div class="col-md-4">
            <select id="locationFilter" class="form-select">
                <option value="">All Locations</option>
                @foreach($users->pluck('alamat')->unique()->filter()->values() as $location)
                    <option value="{{ $location }}">{{ $location }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- 👤 User Cards -->
    <div class="row g-4" id="userGrid">
        @foreach ($users as $user)
            <div class="col-6 col-md-4 col-lg-3 user-card"
                 data-name="{{ strtolower($user->name) }}"
                 data-email="{{ strtolower($user->email) }}"
                 data-location="{{ strtolower($user->alamat ?? '') }}">
                <a href="{{ route('user.detail', $user->id) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm rounded-4 hover-card h-100">
                        <div class="img-container">
                            <img src="{{ $user->gambar ? asset('storage/' . $user->gambar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                 class="user-image" 
                                 alt="{{ $user->name }}">
                        </div>
                        <div class="card-body bg-white p-3">
                            <h5 class="fw-semibold mb-1">{{ $user->name }}</h5>
                            <p class="text-muted small mb-0">{{ $user->alamat ?? 'Unknown location' }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- 🔧 JS for Search & Filter -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const locationFilter = document.getElementById("locationFilter");
        const cards = document.querySelectorAll(".user-card");

        function filterUsers() {
            const search = searchInput.value.toLowerCase();
            const location = locationFilter.value.toLowerCase();

            cards.forEach(card => {
                const name = card.dataset.name;
                const email = card.dataset.email;
                const loc = card.dataset.location;

                const matchesSearch = name.includes(search) || email.includes(search);
                const matchesLocation = !location || loc === location;

                card.style.display = (matchesSearch && matchesLocation) ? "block" : "none";
            });
        }

        searchInput.addEventListener("input", filterUsers);
        locationFilter.addEventListener("change", filterUsers);
    });
</script>
@endsection
