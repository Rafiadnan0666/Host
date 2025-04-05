@extends('layout.dash')
@section('konten')
<div class="row mb-4">
    <div class="col-md-12">
        <form action="{{ route('admin') }}" method="GET" class="form-inline">
            <label class="me-2">From:</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control me-3">
            <label class="me-2">To:</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control me-3">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Daily Sales Card -->
        <div class="card card-primary card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Daily Sales</div>
                </div>
                <div class="card-category">{{ $startDate }} to {{ $endDate }}</div>
            </div>
            <div class="card-body pb-0">
                <div class="mb-4 mt-2">
                    <h1>Rp. {{ number_format($totalSales, 2) }}</h1>
                </div>
                <div class="pull-in">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- User Registrations Card -->
    <div class="col-md-6">
        <div class="card card-round">
            <div class="card-body pb-0">
                <div class="h1 fw-bold float-end text-primary">
                    {{ $userGrowthPercentage }}%
                </div>
                <h2 class="mb-2">{{ $totalUsers }}</h2>
                <p class="text-muted">Total User Registrations</p>
                <div class="pull-in sparkline-fix">
                    <canvas id="userRegistrationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User List Section -->
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">User List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="multi-filter-select" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user as $userItem)
                            <tr>
                                <td>{{ $userItem->name }}</td>
                                <td>{{ $userItem->email }}</td>
                                <td>{{ $userItem->alamat }}</td>
                                <td>{{ $userItem->no_telp }}</td>
                                <td>{{ strtoupper($userItem->role) }}</td>
                                <td>{{ $userItem->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dailyCtx = document.getElementById("dailySalesChart").getContext("2d");
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesData['labels'] ?? []) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($salesData['data'] ?? []) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const userCtx = document.getElementById("userRegistrationsChart").getContext("2d");
    new Chart(userCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels'] ?? []) !!},
            datasets: [{
                label: 'User Registrations',
                data: {!! json_encode($chartData['userRegistrations'] ?? []) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection
