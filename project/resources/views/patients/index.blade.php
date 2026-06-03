@extends('layouts.dashboard')
@section('title', 'Patients')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Patient List</span>
            <button class="btn btn-primary btn-sm" onclick="openModal()">
                <i class="fas fa-plus"></i> Add Patient
            </button>
        </div>
        <div class="card-body">
            <table id="patientsTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Age/DOB</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="patientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="patientForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="patient_id" name="id">
                        <div class="mb-3">
                            <label>Name *</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <textarea name="address" id="address" class="form-control" rows="2"></textarea>
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
        $(document).ready(function () {
            table = $('#patientsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('patients.index') }}",
                columns: [
                    { data: 'patient_code' },
                    { data: 'name' },
                    { data: 'phone' },
                    { data: 'gender' },
                    { 
                        data: 'dob',
                        render: function (data) {
                            if (!data) return '';
                            let d = new Date(data);
                            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-info text-white" onclick="editPatient(${data})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deletePatient(${data})"><i class="fas fa-trash"></i></button>
                            `;
                        }
                    }
                ]
            });

            $('#patientForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('patients.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#patientModal').modal('hide');
                        toastr.success(response.success);
                        table.ajax.reload();
                        $('#saveBtn').prop('disabled', false).text('Save');
                    },
                    error: function (xhr) {
                        $('#saveBtn').prop('disabled', false).text('Save');
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    }
                });
            });
        });

        function openModal() {
            $('#patientForm')[0].reset();
            $('#patient_id').val('');
            $('#modalTitle').text('Add Patient');
            $('#patientModal').modal('show');
        }

        function editPatient(id) {
            $.get(`/patients/${id}/edit`, function (data) {
                $('#patient_id').val(data.id);
                $('#name').val(data.name);
                $('#phone').val(data.phone);
                $('#gender').val(data.gender);
                $('#dob').val(data.dob);
                $('#address').val(data.address);
                $('#notes').val(data.notes);
                $('#modalTitle').text('Edit Patient');
                $('#patientModal').modal('show');
            });
        }

        function deletePatient(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this patient?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/patients/${id}`,
                        type: 'DELETE',
                        success: function (response) {
                            toastr.success(response.success);
                            table.ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush