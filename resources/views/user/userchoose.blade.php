@extends('layout.dash')

@section('konten')
<div class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <img src="{{ asset('storage/' . $user->gambar) }}" class="card-img-top" style="aspect-ratio: 1 / 1; object-fit: cover;" alt="{{ $user->name }}">
                <div class="card-body">
                    <h4 class="card-title mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3"><i class="bi bi-geo-alt-fill"></i> {{ $user->alamat ?? 'Tidak tersedia' }}</p>

                    @if (session('success'))
                        <div class="alert alert-success small">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('user.order', $user->id) }}">
                        @csrf

                        <!-- Order Time -->
                        <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu Order</label>
                            <input type="datetime-local" id="waktu" name="waktu" class="form-control @error('waktu') is-invalid @enderror" value="{{ old('waktu') }}">
                            @error('waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deadline Time -->
                        <div class="mb-3">
                            <label for="batas" class="form-label">Batas Waktu</label>
                            <input type="datetime-local" id="batas" name="batas" class="form-control @error('batas') is-invalid @enderror" value="{{ old('batas') }}">
                            @error('batas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration Preview -->
                        <div id="durationPreview" class="text-end small text-muted mb-1" style="display: none;">
                            Durasi: <span id="durationText">0</span> jam
                        </div>

                        <!-- Cost Preview -->
                        <div id="costPreview" class="text-end mb-3 fw-bold text-success" style="display: none;">
                            Estimasi biaya: Rp <span id="costText">0</span>
                        </div>

                        <!-- Cost Input (Readonly) -->
                        <div class="mb-3">
                            <label for="cost" class="form-label">Biaya (Rp)</label>
                            <input type="number" id="cost" name="cost" class="form-control bg-light @error('cost') is-invalid @enderror" readonly>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location Input -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" class="btn btn-primary w-100" disabled>Kirim Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Input Elements
            const waktuInput = document.getElementById('waktu');
            const batasInput = document.getElementById('batas');
            const costInput = document.getElementById('cost');
            const costText = document.getElementById('costText');
            const costPreview = document.getElementById('costPreview');
            const durationText = document.getElementById('durationText');
            const durationPreview = document.getElementById('durationPreview');
            const submitBtn = document.getElementById('submitBtn');
    
            const ratePerHour = 50000;
    
            function resetFields() {
                costInput.value = '';
                costText.textContent = '0';
                durationText.textContent = '0';
                costPreview.style.display = 'none';
                durationPreview.style.display = 'none';
                submitBtn.disabled = true;
            }
    
            function calculateCost() {
                const waktuValue = waktuInput.value;
                const batasValue = batasInput.value;
    
                if (!waktuValue || !batasValue) {
                    resetFields();
                    return;
                }
    
                const waktu = new Date(waktuValue);
                const batas = new Date(batasValue);
    
                if (isNaN(waktu.getTime()) || isNaN(batas.getTime()) || batas <= waktu) {
                    resetFields();
                    return;
                }
    
                const diffInMs = batas - waktu;
                const diffInHours = diffInMs / (1000 * 60 * 60);
                const duration = Math.ceil(diffInHours); 
                const cost = duration * ratePerHour;
    
                // Update UI
                costInput.value = cost;
                costText.textContent = cost.toLocaleString('id-ID');
                durationText.textContent = duration;
                costPreview.style.display = 'block';
                durationPreview.style.display = 'block';
                submitBtn.disabled = false;
            }
    
            // Auto-calculate on input change
            waktuInput.addEventListener('change', calculateCost);
            batasInput.addEventListener('change', calculateCost);
        });
    </script>
</div>
@endsection



