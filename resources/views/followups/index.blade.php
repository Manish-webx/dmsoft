@extends('layouts.dashboard')
@section('title', 'Pending Follow-ups')
@section('content')

<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row align-items-end">
            <div class="col-md-3 mb-3 mb-md-0">
                <label>Date From</label>
                <input type="date" name="date_from" id="date_from" class="form-control">
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <label>Date To</label>
                <input type="date" name="date_to" id="date_to" class="form-control">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilter()"><i class="fas fa-sync"></i> Reset</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-warning text-dark">
        <i class="fas fa-bell"></i> Pending Follow-ups
    </div>
    <div class="card-body">
        <table id="followupsTable" class="table table-bordered table-hover w-100">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient Name</th>
                    <th>Phone</th>
                    <th>Doctor</th>
                    <th>Notes</th>
                    <th>Status</th>
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
        table = $('#followupsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('followups.index') }}",
                data: function(d) {
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                }
            },
            columns: [
                { 
                    data: 'appointment_date',
                    render: function(data) {
                        if (!data) return '';
                        let d = new Date(data);
                        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                        let timeStr = d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear() + ' ' + timeStr;
                    }
                },
                { data: 'patient.name' },
                { data: 'patient.phone' },
                { data: 'doctor.name' },
                { data: 'notes' },
                { 
                    data: 'status',
                    render: function(data) {
                        return `<span class="badge bg-warning text-dark">${data}</span>`;
                    }
                }
            ],
            order: [[0, 'asc']]
        });

        $('#filterForm').submit(function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });

    function resetFilter() {
        $('#date_from').val('');
        $('#date_to').val('');
        table.ajax.reload();
    }
</script>
@endpush
