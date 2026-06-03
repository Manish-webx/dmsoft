@extends('layouts.dashboard')

@section('title', 'Dashboard Overview')

@section('content')
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2 bg-primary text-white rounded-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Today's Appointments</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $todaysAppointments }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2 bg-success text-white rounded-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Patients</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalPatients }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2 bg-warning text-dark rounded-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Today's Follow-ups</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $todaysFollowups }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2 bg-danger text-white rounded-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Low Stock Medicines</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $lowStockMedicines }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Appointments -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Appointments</h6>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->name }}</td>
                                <td>{{ $appointment->doctor->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y h:i A') }}</td>
                                <td>
                                    @if($appointment->status == 'Confirmed')
                                        <span class="badge bg-primary">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'Visited')
                                        <span class="badge bg-success">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'Follow-up')
                                        <span class="badge bg-warning text-dark">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'No Show')
                                        <span class="badge bg-danger">{{ $appointment->status }}</span>
                                    @else
                                        <span class="badge bg-info text-dark">{{ $appointment->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No recent appointments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Prescriptions -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-success">Recent Prescriptions</h6>
                <a href="{{ route('prescriptions.index') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPrescriptions as $prescription)
                            <tr>
                                <td>{{ $prescription->patient->name }}</td>
                                <td>{{ $prescription->doctor->name }}</td>
                                <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('prescriptions.pdf', $prescription->id) }}" class="btn btn-sm btn-danger" target="_blank" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No recent prescriptions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
