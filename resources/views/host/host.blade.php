@extends('layout.dash')

@section('konten')
<div class=" my-5">
    <!-- 👤 Profile Section -->
    <div class="card shadow-sm mb-5 p-4 border-0 rounded-4">
        <div class="d-flex align-items-center">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                class="rounded-circle shadow-sm me-4" width="90" height="90" alt="Profile Image">
            <div>
                <h3 class="mb-0">{{ $user->name }}</h3>
                <small class="text-muted">{{ $user->email }}</small>
                <p class="mt-2">{{ $user->bio ?? 'No bio added yet. Go stand out!' }}</p>
            </div>
        </div>
    </div>

    <!-- 📅 Filter Form -->
    <form method="GET" class="row gy-2 gx-3 align-items-center mb-4">
        <div class="col-auto">
            <label for="from" class="form-label fw-semibold">From</label>
            <input type="date" id="from" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-auto">
            <label for="to" class="form-label fw-semibold">To</label>
            <input type="date" id="to" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-auto mt-4">
            <button class="btn btn-success"><i class="bi bi-funnel-fill"></i> Filter</button>
        </div>
    </form>

    <!-- 📈 Stats Chart -->
    <div class="card shadow-sm border-0 p-4 mb-5 rounded-4">
        <h5 class="mb-3"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Order Statistics</h5>
        <canvas id="orderChart" height="100"></canvas>
    </div>

    <!-- 📦 Orders List -->
    <div class="card shadow-sm border-0 p-4 rounded-4">
        <h5 class="mb-4"><i class="bi bi-card-list me-2 text-success"></i>Orders Overview</h5>
    
        @if ($orders->isEmpty())
            <p class="text-muted">No orders found for the selected period.</p>
        @else
            @foreach ($orders->groupBy('pesan_id') as $pesanId => $group)
                @php $firstOrder = $group->first(); @endphp
    
                @if (Auth::user()->id === $firstOrder->pesanan_id)
                    <div class="mb-3">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="bi bi-person-circle me-2"></i>For: {{ $firstOrder->pesan->name ?? 'Unknown User' }}
                        </h6>
    
                        @foreach ($group as $order)
                            <div class="border rounded-3 p-3 mb-3 bg-light-subtle">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 text-dark">
                                            <i class="bi bi-clipboard-check me-1 text-info"></i>
                                            {{ $order->description ?? 'Custom Order' }}
                                        </h6>
                                        <p class="mb-1 text-muted small">
                                            <strong>Pemesan:</strong> {{ $order->pemesan->name ?? 'N/A' }}<br>
                                            <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($order->waktu)->format('d M Y H:i') }} → 
                                            {{ \Carbon\Carbon::parse($order->batas)->format('d M Y H:i') }}<br>
                                            <strong>Location:</strong> {{ $order->location }}
                                        </p>
                                    </div>
    
                                    <div class="text-end">
                                        <span class="badge rounded-pill px-3 py-1 {{ $order->approve === 'y' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $order->approve === 'y' ? 'Approved' : 'Pending' }}
                                        </span>
                                        <div class="text-muted small mt-2">
                                            <strong>Rp</strong>{{ number_format($order->cost, 0, ',', '.') }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar3 me-1"></i>Created: {{ $order->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
    </div>
    
</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('orderChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($orderStats->pluck('date')) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($orderStats->pluck('count')) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Orders'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
