@extends('layouts.dashboard')
@section('title', 'Patients')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Patient List {{ request('trashed') ? '(Trash)' : '' }}</span>
            <div>
                @if(request('trashed'))
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to List</a>
                @else
                    <a href="{{ route('patients.index', ['trashed' => 1]) }}" class="btn btn-warning btn-sm me-2"><i class="fas fa-trash"></i> View Trash</a>
                    <button class="btn btn-primary btn-sm" onclick="openModal()">
                        <i class="fas fa-plus"></i> Add Patient
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table id="patientsTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Source</th>
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
                            <label>Age</label>
                            <input type="text" name="dob" id="dob" class="form-control" placeholder="e.g. 25">
                        </div>
                        <div class="mb-3">
                            <label>Source (Where did you hear about us?)</label>
                            <select name="source" id="source" class="form-select select2-source" style="width: 100%;">
                                <option value="">Select...</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Google">Google</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Youtube">Youtube</option>
                                <option value="LinkedIn">LinkedIn</option>
                            </select>
                            <small class="text-muted">Select from list or type a custom option</small>
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
            $('.select2-source').select2({
                theme: 'bootstrap-5',
                tags: true,
                dropdownParent: $('#patientModal')
            });

            table = $('#patientsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('patients.index') }}",
                    data: function(d) {
                        d.trashed = "{{ request('trashed', 0) }}";
                    }
                },
                columns: [
                    { data: 'patient_code' },
                    { data: 'name' },
                    { data: 'phone' },
                    { data: 'gender' },
                    { data: 'dob' },
                    { 
                        data: 'source',
                        render: function(data) {
                            return data ? `<span class="badge bg-secondary">${data}</span>` : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            let isTrashed = "{{ request('trashed', 0) }}";
                            if (isTrashed == "1") {
                                return `
                                    <button class="btn btn-sm btn-success" onclick="restorePatient(${data})"><i class="fas fa-undo"></i> Restore</button>
                                    <button class="btn btn-sm btn-danger" onclick="forceDeletePatient(${data})"><i class="fas fa-times"></i> Delete Permanently</button>
                                `;
                            } else {
                                return `
                                    <button class="btn btn-sm btn-info text-white" onclick="editPatient(${data})"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deletePatient(${data})"><i class="fas fa-trash"></i></button>
                                `;
                            }
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
            $('#source').val('').trigger('change');
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
                
                // For select2, if the value doesn't exist, we might need to add it before selecting
                if(data.source && $('#source').find("option[value='" + data.source + "']").length == 0) {
                    var newOption = new Option(data.source, data.source, true, true);
                    $('#source').append(newOption);
                }
                $('#source').val(data.source).trigger('change');
                
                $('#address').val(data.address);
                $('#notes').val(data.notes);
                $('#modalTitle').text('Edit Patient');
                $('#patientModal').modal('show');
            });
        }

        function deletePatient(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this patient? It will be moved to trash.",
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

        function restorePatient(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to restore this patient?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/patients/${id}/restore`,
                        type: 'POST',
                        success: function (response) {
                            toastr.success(response.success);
                            table.ajax.reload();
                        }
                    });
                }
            });
        }

        function forceDeletePatient(id) {
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "This action cannot be undone! This will permanently delete the patient.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete permanently!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/patients/${id}/force-delete`,
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