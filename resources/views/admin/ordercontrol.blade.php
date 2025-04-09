@extends('layout.dash')
@section('konten')
<div class="">
    <h2 class="mb-4">Orders Management</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order</button>
        </div>
        <div class="col-md-6">
            <form action="{{ url('orders.index') }}" method="GET" class="form-inline float-end">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ url('orders.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label>Pemesan</label>
                        <select name="pemesan_id" class="form-control">
                            <option value="">All Pemesan</option>
                            @foreach($user as $pemesan)
                                <option value="{{ $pemesan->id }}" {{ request('pemesan_id') == $pemesan->id ? 'selected' : '' }}>{{ $pemesan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Pesan</label>
                        <select name="pesan_id" class="form-control">
                            <option value="">All Pesan</option>
                            @foreach($user as $pesan)
                                <option value="{{ $pesan->id }}" {{ request('pesan_id') == $pesan->id ? 'selected' : '' }}>{{ $pesan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Approve Status</label>
                        <select name="approve" class="form-control">
                            <option value="">All</option>
                            <option value="y" {{ request('approve') == 'y' ? 'selected' : '' }}>Approved</option>
                            <option value="n" {{ request('approve') == 'n' ? 'selected' : '' }}>Not Approved</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url('orders.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Graph -->
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="orderChart"></canvas>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID 
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Pemesan
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'pemesan_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Pesan
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'pesan_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Waktu
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'waktu', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Cost
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'cost', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Approve
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'approve', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Batas
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'batas', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Location
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->pemesan->name }}</td>
                    <td>{{ $order->pesan->name }}</td>
                    <td>{{ $order->waktu }}</td>
                    <td>{{ $order->cost }}</td>
                    <td>
                        @if($order->approve == 'y')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>{{ $order->batas }}</td>
                    <td>{{ $order->location }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">Edit</button>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                
                <!-- Edit Order Modal -->
                <div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Order</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label>Pemesan</label>
                                        <select name="pemesan_id" class="form-control" required>
                                            @foreach($user as $pemesan)
                                                <option value="{{ $pemesan->id }}" {{ $order->pemesan_id == $pemesan->id ? 'selected' : '' }}>{{ $pemesan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Pesan</label>
                                        <select name="pesan_id" class="form-control" required>
                                            @foreach($user as $pesan)
                                                <option value="{{ $pesan->id }}" {{ $order->pesan_id == $pesan->id ? 'selected' : '' }}>{{ $pesan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Waktu</label>
                                        <input type="datetime-local" name="waktu" value="{{ date('Y-m-d\TH:i', strtotime($order->waktu)) }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Cost</label>
                                        <input type="number" name="cost" value="{{ $order->cost }}" class="form-control" required min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label>Approve</label>
                                        <select name="approve" class="form-control" required>
                                            <option value="y" {{ $order->approve == 'y' ? 'selected' : '' }}>Yes</option>
                                            <option value="n" {{ $order->approve == 'n' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Batas</label>
                                        <input type="datetime-local" name="batas" value="{{ date('Y-m-d\TH:i', strtotime($order->batas)) }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Location</label>
                                        <input type="text" name="location" value="{{ $order->location }}" class="form-control" required>
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center mt-3">
            {{-- {{ $orders->appends(request()->query())->links() }} --}}
        </div>
    </div>
</div>

<!-- Add Order Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Pemesan</label>
                        <select name="pemesan_id" class="form-control" required>
                            @foreach($user as $pemesan)
                                <option value="{{ $pemesan->id }}">{{ $pemesan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Pesan</label>
                        <select name="pesan_id" class="form-control" required>
                            @foreach($user as $pesan)
                                <option value="{{ $pesan->id }}">{{ $pesan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Waktu</label>
                        <input type="datetime-local" name="waktu" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Cost</label>
                        <input type="number" name="cost" placeholder="Cost" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label>Approve</label>
                        <select name="approve" class="form-control" required>
                            <option value="y">Yes</option>
                            <option value="n">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Batas</label>
                        <input type="datetime-local" name="batas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" name="location" placeholder="Location" class="form-control" required>
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <button type="submit" class="btn btn-success">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('orderChart').getContext('2d');

        const ordersData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ordersData.labels,
                datasets: [{
                    label: 'Orders Created',
                    data: ordersData.counts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Orders Trend'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection