@extends('layout.dash')
@section('konten')
<div class="">
    <h2 class="mb-4">Orders Management</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order</button>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Order Graph -->
    <div class="card mb-4">
        {{-- <div class="card-body">
            <canvas id="orderChart"></canvas>
        </div> --}}
    </div>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pemesan</th>
                <th>Pesan</th>
                <th>Waktu</th>
                <th>Cost</th>
                <th>Approve</th>
                <th>Batas</th>
                <th>Location</th>
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
                <td>{{ $order->approve }}</td>
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
                                <select name="pemesan_id" class="form-control mb-2" required>
                                    @foreach($user as $pemesan)
                                        <option value="{{ $pemesan->id }}" {{ $order->pemesan_id == $pemesan->id ? 'selected' : '' }}>{{ $pemesan->name }}</option>
                                    @endforeach
                                </select>
                                <select name="pesan_id" class="form-control mb-2" required>
                                    @foreach($user as $pesan)
                                        <option value="{{ $pesan->id }}" {{ $order->pesan_id == $pesan->id ? 'selected' : '' }}>{{ $pesan->name }}</option>
                                    @endforeach
                                </select>
                                <input type="datetime-local" name="waktu" value="{{ $order->waktu }}" class="form-control mb-2" required>
                                <input type="number" name="cost" value="{{ $order->cost }}" class="form-control mb-2" required min="0">
                                <select name="approve" class="form-control mb-2" required>
                                    <option value="y" {{ $order->approve == 'y' ? 'selected' : '' }}>Yes</option>
                                    <option value="n" {{ $order->approve == 'n' ? 'selected' : '' }}>No</option>
                                </select>
                                <input type="datetime-local" name="batas" value="{{ $order->batas }}" class="form-control mb-2" required>
                                <input type="text" name="location" value="{{ $order->location }}" class="form-control mb-2" required>
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
                    <select name="pemesan_id" class="form-control mb-2" required>
                        @foreach($user as $pemesan)
                            <option value="{{ $pemesan->id }}">{{ $pemesan->name }}</option>
                        @endforeach
                    </select>
                    <select name="pesan_id" class="form-control mb-2" required>
                        @foreach($user as $pesan)
                            <option value="{{ $pesan->id }}">{{ $pesan->name }}</option>
                        @endforeach
                    </select>
                    <input type="datetime-local" name="waktu" class="form-control mb-2" required>
                    <input type="number" name="cost" placeholder="Cost" class="form-control mb-2" required min="0">
                    <select name="approve" class="form-control mb-2" required>
                        <option value="y">Yes</option>
                        <option value="n">No</option>
                    </select>
                    <input type="datetime-local" name="batas" class="form-control mb-2" required>
                    <input type="text" name="location" placeholder="Location" class="form-control mb-2" required>
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
                    borderWidth: 2
                }]
            },
            options: {}
        });
    });
</script>
@endsection
