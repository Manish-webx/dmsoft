@extends('layouts.dashboard')
@section('title', 'Billing')
@section('content')

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-money-bill-wave"></i> Total Billed</h6>
                <h3 class="mb-0" id="cardTotalRevenue">₹0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-hand-holding-usd"></i> Total Received</h6>
                <h3 class="mb-0" id="cardTotalReceived">₹0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Total Due</h6>
                <h3 class="mb-0" id="cardTotalDue">₹0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-prescription"></i> Prescriptions</h6>
                <h3 class="mb-0" id="cardTotalCount">0</h3>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset Today</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table id="revenueTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Prescription #</th>
                    <th>Final Amount</th>
                    <th>Received</th>
                    <th>Due</th>
                    <th>Payment Mode</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let table;
    $(document).ready(function() {
        table = $('#revenueTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('revenues.index') }}",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
                dataSrc: function(json) {
                    // Update Summary Cards
                    $('#cardTotalRevenue').text('₹' + parseFloat(json.totals.final_amount).toFixed(2));
                    $('#cardTotalReceived').text('₹' + parseFloat(json.totals.received_amount).toFixed(2));
                    $('#cardTotalDue').text('₹' + parseFloat(json.totals.due_amount).toFixed(2));
                    $('#cardTotalCount').text(json.totals.count);
                    return json.data;
                }
            },
            columns: [
                { 
                    data: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                { 
                    data: 'patient',
                    render: function(data) {
                        return data ? (data.patient_code + ' - ' + data.name) : 'N/A';
                    }
                },
                { 
                    data: 'prescription_id',
                    render: function(data) {
                        return '#' + data.toString().padStart(5, '0');
                    }
                },
                { 
                    data: 'final_amount',
                    render: function(data) {
                        return '₹' + parseFloat(data).toFixed(2);
                    }
                },
                { 
                    data: 'received_amount',
                    render: function(data) {
                        return '₹' + parseFloat(data).toFixed(2);
                    }
                },
                { 
                    data: 'due_amount',
                    render: function(data) {
                        let amt = parseFloat(data).toFixed(2);
                        return amt > 0 ? `<span class="text-danger fw-bold">₹${amt}</span>` : `₹${amt}`;
                    }
                },
                { data: 'payment_mode' }
            ],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            order: [[0, 'desc']]
        });

        $('#filterForm').submit(function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });

    function resetFilters() {
        let today = new Date().toISOString().split('T')[0];
        $('#start_date').val(today);
        $('#end_date').val(today);
        table.ajax.reload();
    }
</script>
@endpush
