@extends('layouts.dashboard')
@section('title', 'Prescriptions')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Prescription List</span>
            <a href="{{ route('prescriptions.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Write Prescription
            </a>
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
                ajax: "{{ route('prescriptions.index') }}",
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
                            let editUrl = '{{ route("prescriptions.edit", ":id") }}'.replace(':id', data);
                            return `
                                    <a href="${editUrl}" class="btn btn-sm btn-info" title="Edit Prescription"><i class="fas fa-edit"></i></a>
                                    <a href="${viewPdfUrl}" target="_blank" class="btn btn-sm btn-primary" title="View PDF"><i class="fas fa-eye"></i></a>
                                    <a href="${pdfUrl}" class="btn btn-sm btn-secondary" title="Download PDF"><i class="fas fa-download"></i></a>
                                `;
                        }
                    }
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush