@extends('layouts.dashboard')
@section('title', 'Prescriptions')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Prescription List {{ request('trashed') ? '(Trash)' : '' }}</span>
            <div>
                @if(request('trashed'))
                    <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to List</a>
                @else
                    <a href="{{ route('prescriptions.index', ['trashed' => 1]) }}" class="btn btn-warning btn-sm me-2"><i class="fas fa-trash"></i> View Trash</a>
                    <a href="{{ route('prescriptions.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Write Prescription
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table id="prescriptionsTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Problem</th>
                        <th>Next Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#prescriptionsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('prescriptions.index') }}",
                    data: function(d) {
                        d.trashed = "{{ request('trashed', 0) }}";
                    }
                },
                columns: [
                    { data: 'patient.patient_code' },
                    {
                        data: 'date',
                        render: function (data) {
                            if (!data) return '';
                            let d = new Date(data);
                            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                        }
                    },
                    { data: 'patient.name' },
                    { data: 'doctor.name' },
                    {
                        data: 'problem.name',
                        render: function (data) {
                            return data ? `<span class="badge bg-secondary">${data}</span>` : '<span class="text-muted">None</span>';
                        }
                    },
                    {
                        data: 'next_followup_date',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">None</span>';
                            let d = new Date(data);
                            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            let pdfUrl = '{{ route("prescriptions.pdf", ":id") }}'.replace(':id', data);
                            let viewPdfUrl = '{{ route("prescriptions.viewPdf", ":id") }}'.replace(':id', data);
                            let billingPdfUrl = '{{ route("prescriptions.billingPdf", ":id") }}'.replace(':id', data);
                            let viewBillingPdfUrl = '{{ route("prescriptions.viewBillingPdf", ":id") }}'.replace(':id', data);
                            let editUrl = '{{ route("prescriptions.edit", ":id") }}'.replace(':id', data);
                            let isTrashed = "{{ request('trashed', 0) }}";
                            if (isTrashed == "1") {
                                return `
                                    <button class="btn btn-sm btn-success" onclick="restorePrescription(${data})"><i class="fas fa-undo"></i> Restore</button>
                                    <button class="btn btn-sm btn-danger" onclick="forceDeletePrescription(${data})"><i class="fas fa-times"></i> Delete Permanently</button>
                                `;
                            } else {
                                return `
                                    <a href="${editUrl}" class="btn btn-sm btn-info" title="Edit Prescription"><i class="fas fa-edit"></i></a>
                                    <a href="${viewPdfUrl}" target="_blank" class="btn btn-sm btn-primary" title="View Prescription"><i class="fas fa-file-medical"></i></a>
                                    <a href="${pdfUrl}" class="btn btn-sm btn-secondary" title="Download Prescription"><i class="fas fa-download"></i></a>
                                    <a href="${viewBillingPdfUrl}" target="_blank" class="btn btn-sm btn-warning" title="View Bill"><i class="fas fa-file-invoice-dollar"></i></a>
                                    <a href="${billingPdfUrl}" class="btn btn-sm btn-dark" title="Download Bill"><i class="fas fa-file-download"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="deletePrescription(${data})" title="Delete"><i class="fas fa-trash"></i></button>
                                `;
                            }
                        }
                    }
                ],
                order: [[6, 'desc']]
            });
        });

        function deletePrescription(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this prescription? It will be moved to trash.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/prescriptions/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            toastr.success(response.success);
                            $('#prescriptionsTable').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }

        function restorePrescription(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to restore this prescription?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/prescriptions/${id}/restore`,
                        type: 'POST',
                        success: function(response) {
                            toastr.success(response.success);
                            $('#prescriptionsTable').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }

        function forceDeletePrescription(id) {
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "This will permanently delete the prescription.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete permanently!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/prescriptions/${id}/force-delete`,
                        type: 'DELETE',
                        success: function(response) {
                            toastr.success(response.success);
                            $('#prescriptionsTable').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush