@extends('layouts.dashboard')
@section('title', 'Leads Management')
@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-funnel-dollar text-primary me-2"></i>
                    Leads Management {{ request('trashed') ? '(Trash)' : '' }}
                </h5>
                <small class="text-muted">Inquiries received from external websites and marketing campaigns</small>
            </div>
            <div>
                @if(request('trashed'))
                    <a href="{{ route('leads.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Active Leads
                    </a>
                @else
                    <a href="{{ route('leads.index', ['trashed' => 1]) }}" class="btn btn-outline-danger btn-sm me-2">
                        <i class="fas fa-trash-alt me-1"></i> View Trash
                    </a>
                    <button class="btn btn-primary btn-sm" onclick="openModal()">
                        <i class="fas fa-plus me-1"></i> Add Lead
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="leadsTable" class="table table-hover table-bordered w-100 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 130px;">Date</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Message / Inquiry</th>
                            <th>Sales Remark</th>
                            <th style="width: 140px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lead Modal -->
    <div class="modal fade" id="leadModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="leadForm">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTitle">Add Lead</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" id="lead_id" name="id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lead Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. John Doe" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g. +91 9876543210" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="e.g. john@example.com">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Location / Clinic Branch</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                    <input type="text" name="location" id="location" class="form-control" placeholder="e.g. Gurgaon / Patel Nagar">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Website Message / Inquiry Details</label>
                                <textarea name="message" id="message" class="form-control" rows="3" placeholder="Inquiry message submitted by the customer from the website..."></textarea>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-dark d-flex align-items-center">
                                        <i class="fas fa-comment-dots text-primary me-2"></i> Sales Person Remark / Follow-up Notes
                                    </label>
                                    <textarea name="remark" id="remark" class="form-control" rows="3" placeholder="Enter follow-up status, call notes, qualification remarks, or next steps..."></textarea>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i> Update this field after calling or contacting the lead.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="fas fa-save me-1"></i> Save Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let leadsTable;

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }

        $(document).ready(function () {
            leadsTable = $('#leadsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('leads.index') }}",
                    data: function (d) {
                        d.trashed = "{{ request('trashed', 0) }}";
                    }
                },
                columns: [
                    {
                        data: 'created_at',
                        render: function (data) {
                            return `<span class="small text-muted fw-semibold">${formatDate(data)}</span>`;
                        }
                    },
                    {
                        data: 'name',
                        render: function (data) {
                            return `<span class="fw-bold text-dark">${data || '-'}</span>`;
                        }
                    },
                    {
                        data: 'phone',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `
                                <a href="tel:${data}" class="text-decoration-none fw-semibold text-primary">
                                    <i class="fas fa-phone-alt me-1 small"></i>${data}
                                </a>
                            `;
                        }
                    },
                    {
                        data: 'email',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `<a href="mailto:${data}" class="text-decoration-none text-secondary small">${data}</a>`;
                        }
                    },
                    {
                        data: 'location',
                        render: function (data) {
                            return data
                                ? `<span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt text-danger me-1"></i>${data}</span>`
                                : '<span class="text-muted small">-</span>';
                        }
                    },
                    {
                        data: 'message',
                        render: function (data) {
                            if (!data) return '<span class="text-muted small">No message</span>';
                            let trimmed = data.length > 60 ? data.substring(0, 60) + '...' : data;
                            return `<span class="small" title="${data.replace(/"/g, '&quot;')}">${trimmed}</span>`;
                        }
                    },
                    {
                        data: 'remark',
                        render: function (data) {
                            if (!data) {
                                return `<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-start me-1"></i>No Remark</span>`;
                            }
                            let trimmed = data.length > 50 ? data.substring(0, 50) + '...' : data;
                            return `
                                <div class="p-2 rounded bg-light border border-info border-opacity-25 small" title="${data.replace(/"/g, '&quot;')}">
                                    <i class="fas fa-comment-dots text-info me-1"></i> ${trimmed}
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        render: function (data, type, row) {
                            let isTrashed = "{{ request('trashed', 0) }}";
                            if (isTrashed == "1") {
                                return `
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-success" onclick="restoreLead(${data})" title="Restore Lead">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button class="btn btn-danger" onclick="forceDeleteLead(${data})" title="Delete Permanently">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="editLead(${data})" title="Edit & Update Remark">
                                            <i class="fas fa-edit me-1"></i> Remark / Edit
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="deleteLead(${data})" title="Move to Trash">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    }
                ],
                order: [[0, 'desc']]
            });

            $('#leadForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

                $.ajax({
                    url: "{{ route('leads.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#leadModal').modal('hide');
                        toastr.success(response.success || 'Lead saved successfully.');
                        leadsTable.ajax.reload();
                        $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Lead');
                    },
                    error: function (xhr) {
                        $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Lead');
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let key in errors) {
                                toastr.error(errors[key][0]);
                            }
                        } else {
                            toastr.error('Failed to save lead. Please check input.');
                        }
                    }
                });
            });
        });

        function openModal() {
            $('#leadForm')[0].reset();
            $('#lead_id').val('');
            $('#modalTitle').text('Add New Lead');
            $('#leadModal').modal('show');
        }

        function editLead(id) {
            $.get(`/leads/${id}/edit`, function (data) {
                $('#lead_id').val(data.id);
                $('#name').val(data.name);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#location').val(data.location);
                $('#message').val(data.message);
                $('#remark').val(data.remark);
                $('#modalTitle').text('Edit Lead & Remarks: ' + (data.name || ''));
                $('#leadModal').modal('show');
            }).fail(function () {
                toastr.error('Failed to load lead details.');
            });
        }

        function deleteLead(id) {
            Swal.fire({
                title: 'Move to Trash?',
                text: "You want to move this lead to trash? You can restore it later.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, move to trash'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/leads/${id}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            toastr.success(response.success || 'Lead moved to trash.');
                            leadsTable.ajax.reload();
                        },
                        error: function () {
                            toastr.error('Failed to delete lead.');
                        }
                    });
                }
            });
        }

        function restoreLead(id) {
            $.ajax({
                url: `/leads/${id}/restore`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.success(response.success || 'Lead restored successfully.');
                    leadsTable.ajax.reload();
                },
                error: function () {
                    toastr.error('Failed to restore lead.');
                }
            });
        }

        function forceDeleteLead(id) {
            Swal.fire({
                title: 'Permanently Delete?',
                text: "This action cannot be undone!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete permanently'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/leads/${id}/force-delete`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            toastr.success(response.success || 'Lead permanently deleted.');
                            leadsTable.ajax.reload();
                        },
                        error: function () {
                            toastr.error('Failed to delete lead permanently.');
                        }
                    });
                }
            });
        }
    </script>
@endpush
