@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-content wrapper py-4">
        <div class="container-fluid">
            {{-- System Header --}}
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="mb-1">{{ $settings->title ?? 'My Application' }}</h2>
                    <p class="text-muted mb-0">Welcome back! Here's an overview of your system metrics.</p>
                </div>
                <div>
                    <img src="{{ $settings->logo ? asset($settings->logo) : asset('images/default-logo.png') }}"
                        alt="Logo" class="rounded-circle shadow" width="60" height="60">
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-primary mb-3"></i>
                            <h6>Total Users</h6>
                            <p class="display-6 mb-0">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-check fa-2x text-success mb-3"></i>
                            <h6>Active Users</h6>
                            <p class="display-6 mb-0 text-success">{{ $activeUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-times fa-2x text-danger mb-3"></i>
                            <h6>Inactive Users</h6>
                            <p class="display-6 mb-0 text-danger">{{ $inactiveUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-contract fa-2x text-info mb-3"></i>
                            <h6>Active Subs</h6>
                            <p class="display-6 mb-0 text-info">{{ $activeSubs }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Plan Breakdown --}}
            <div class="mt-5">
                <h4 class="mb-3">Subscription Plans</h4>
                <div class="row g-4">
                    @foreach ($plans as $plan)
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-light text-center">
                                    <h6 class="mb-0">{{ $plan['name'] }} Plan</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="fs-3 mb-1">${{ number_format($plan['price'], 2) }}</p>
                                    <span class="badge bg-primary px-3 py-2">{{ $plan['users'] }} Users</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TouchPoint Overview --}}
            <div class="mt-5">
                <h4 class="mb-3">TouchPoint Summary</h4>
                <div class="row g-4 text-center">
                    <div class="col-6 col-md-3">
                        <div class="bg-danger text-white p-4 rounded shadow-sm">
                            <h6>Overdue</h6>
                            <p class="fs-3 mb-0">{{ $overdueCount }}</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="bg-primary text-white p-4 rounded shadow-sm">
                            <h6>Today</h6>
                            <p class="fs-3 mb-0">{{ $todayCount }}</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="bg-warning text-dark p-4 rounded shadow-sm">
                            <h6>Upcoming</h6>
                            <p class="fs-3 mb-0">{{ $upcomingCount }}</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="bg-success text-white p-4 rounded shadow-sm">
                            <h6>Completed</h6>
                            <p class="fs-3 mb-0">{{ $completedCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent TouchPoints List --}}
            <div class="mt-5">
                <h4 class="mb-3">Recent TouchPoints</h4>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light position-sticky top-0" style="z-index: 10;">
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (\App\Models\TouchPoint::latest()->take(10)->get() as $tp)
                                        <tr>
                                            <td>{{ $tp->name }}</td>
                                            <td>{{ $tp->touch_point_start_date->format('M d, Y') }}</td>
                                            <td>{{ ucfirst($tp->contact_method) }}</td>
                                            <td>
                                                @if ($tp->is_completed)
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif ($tp->touch_point_start_date->isPast())
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-primary">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
