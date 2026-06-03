@extends('layouts.dashboard')
@section('title', 'Appointments')
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Appointments List</span>
        <button class="btn btn-primary btn-sm" onclick="openModal()">
            <i class="fas fa-plus"></i> New
        </button>
    </div>
    <div class="card-body">
        <table id="appointmentsTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="appointmentForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="appointment_id" name="id">
                    <div class="mb-3">
                        <label>Patient *</label>
                        <select name="patient_id" id="patient_id" class="form-select select2 w-100" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Doctor *</label>
                        <select name="doctor_id" id="doctor_id" class="form-select w-100" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Date & Time *</label>
                        <input type="datetime-local" name="appointment_date" id="appointment_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="New">New</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Visited">Visited</option>
                            <option value="Follow-up">Follow-up</option>
                            <option value="No Show">No Show</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let table;

    $(document).ready(function() {
        // Set default filter dates to today
        let today = new Date().toISOString().split('T')[0];
        $('#date_from').val(today);
        $('#date_to').val(today);

        // Initialize DataTable
        table = $('#appointmentsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('appointments.index') }}",
                data: function(d) {
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                }
            },
            columns: [
                { data: 'patient.patient_code' },
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
                { 
                    data: 'status',
                    render: function(data) {
                        let color = 'info';
                        if(data == 'Confirmed') color = 'primary';
                        if(data == 'Visited') color = 'success';
                        if(data == 'Follow-up') color = 'warning';
                        if(data == 'No Show') color = 'danger';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                },
                { 
                    data: 'id',
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-info text-white py-0 px-1" onclick="editAppointment(${data})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger py-0 px-1" onclick="deleteAppointment(${data})"><i class="fas fa-trash"></i></button>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100]
        });

        $('#filterForm').submit(function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Form Submit
        $('#appointmentForm').submit(function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).text('Saving...');
            
            $.ajax({
                url: "{{ route('appointments.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#appointmentModal').modal('hide');
                    toastr.success(response.success);
                    table.ajax.reload();
                    $('#saveBtn').prop('disabled', false).text('Save');
                },
                error: function(xhr) {
                    $('#saveBtn').prop('disabled', false).text('Save');
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        toastr.error(errors[key][0]);
                    }
                }
            });
        });
        
        // Fix for Select2 inside Bootstrap modal
        $('.select2').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#appointmentModal')
        });
    });

    function openModal() {
        $('#appointmentForm')[0].reset();
        $('#appointment_id').val('');
        $('#patient_id').val('').trigger('change');
        $('#modalTitle').text('Book Appointment');
        $('#appointmentModal').modal('show');
    }

    function editAppointment(id) {
        $.get(`/appointments/${id}/edit`, function(data) {
            $('#appointment_id').val(data.id);
            $('#patient_id').val(data.patient_id).trigger('change');
            $('#doctor_id').val(data.doctor_id);
            // Format datetime for input[type="datetime-local"]
            let date = new Date(data.appointment_date);
            let formattedDate = date.toISOString().slice(0, 16);
            $('#appointment_date').val(formattedDate);
            $('#status').val(data.status);
            $('#notes').val(data.notes);
            
            $('#modalTitle').text('Edit Appointment');
            $('#appointmentModal').modal('show');
        });
    }

    function deleteAppointment(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this appointment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/appointments/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }
    function resetFilter() {
        $('#date_from').val('');
        $('#date_to').val('');
        table.ajax.reload();
    }
</script>
@endpush
