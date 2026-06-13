@extends('layouts.dashboard')
@section('title', 'Manage Problems & YouTube Links')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Problems List {{ request('trashed') ? '(Trash)' : '' }}</span>
        <div>
            @if(request('trashed'))
                <a href="{{ route('problems.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to List</a>
            @else
                <a href="{{ route('problems.index', ['trashed' => 1]) }}" class="btn btn-warning btn-sm me-2"><i class="fas fa-trash"></i> View Trash</a>
                <button class="btn btn-primary btn-sm" onclick="openProblemModal()">
                    <i class="fas fa-plus"></i> Add Problem
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <table id="problemsTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Problem Name</th>
                    <th>YouTube Links Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Problem Modal -->
<div class="modal fade" id="problemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="problemForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Problem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="problem_id">
                    
                    <div class="mb-3">
                        <label>Problem Name *</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="e.g. Back Pain">
                    </div>

                    <div class="mb-3">
                        <label class="d-flex justify-content-between">
                            <span>YouTube Links</span>
                            <button type="button" class="btn btn-sm btn-success" id="addLinkBtn"><i class="fas fa-plus"></i> Add Link</button>
                        </label>
                        <div id="linksContainer">
                            <div class="input-group mb-2 link-row">
                                <input type="url" name="youtube_links[]" class="form-control" placeholder="https://youtube.com/...">
                                <button type="button" class="btn btn-danger remove-link-btn"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Problem</button>
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
        table = $('#problemsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('problems.index') }}",
                data: function(d) {
                    d.trashed = "{{ request('trashed', 0) }}";
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { 
                    data: 'youtube_links',
                    render: function(data) {
                        return data ? data.length : 0;
                    }
                },
                { 
                    data: 'id',
                    render: function(data) {
                        let isTrashed = "{{ request('trashed', 0) }}";
                        if (isTrashed == "1") {
                            return `
                                <button class="btn btn-sm btn-success" onclick="restoreProblem(${data})"><i class="fas fa-undo"></i> Restore</button>
                                <button class="btn btn-sm btn-danger" onclick="forceDeleteProblem(${data})"><i class="fas fa-times"></i> Delete Permanently</button>
                            `;
                        } else {
                            return `
                                <button class="btn btn-sm btn-info" onclick="editProblem(${data})"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProblem(${data})"><i class="fas fa-trash"></i> Delete</button>
                            `;
                        }
                    }
                }
            ]
        });

        $('#addLinkBtn').click(function() {
            $('#linksContainer').append(`
                <div class="input-group mb-2 link-row">
                    <input type="url" name="youtube_links[]" class="form-control" placeholder="https://youtube.com/...">
                    <button type="button" class="btn btn-danger remove-link-btn"><i class="fas fa-times"></i></button>
                </div>
            `);
        });

        $(document).on('click', '.remove-link-btn', function() {
            $(this).closest('.link-row').remove();
        });

        $('#problemForm').submit(function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            $.ajax({
                url: "{{ route('problems.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#problemModal').modal('hide');
                    toastr.success(response.success);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },
                complete: function() {
                    $('#saveBtn').prop('disabled', false).html('Save Problem');
                }
            });
        });
    });

    window.openProblemModal = function() {
        $('#problemForm')[0].reset();
        $('#problem_id').val('');
        $('#modalTitle').text('Add Problem');
        $('#linksContainer').html(`
            <div class="input-group mb-2 link-row">
                <input type="url" name="youtube_links[]" class="form-control" placeholder="https://youtube.com/...">
                <button type="button" class="btn btn-danger remove-link-btn"><i class="fas fa-times"></i></button>
            </div>
        `);
        $('#problemModal').modal('show');
    }

    window.editProblem = function(id) {
        $.get(`/problems/${id}/edit`, function(data) {
            $('#problem_id').val(data.id);
            $('#name').val(data.name);
            $('#modalTitle').text('Edit Problem');
            
            $('#linksContainer').empty();
            if(data.youtube_links && data.youtube_links.length > 0) {
                data.youtube_links.forEach(function(link) {
                    $('#linksContainer').append(`
                        <div class="input-group mb-2 link-row">
                            <input type="url" name="youtube_links[]" class="form-control" value="${link}">
                            <button type="button" class="btn btn-danger remove-link-btn"><i class="fas fa-times"></i></button>
                        </div>
                    `);
                });
            } else {
                $('#linksContainer').append(`
                    <div class="input-group mb-2 link-row">
                        <input type="url" name="youtube_links[]" class="form-control" placeholder="https://youtube.com/...">
                        <button type="button" class="btn btn-danger remove-link-btn"><i class="fas fa-times"></i></button>
                    </div>
                `);
            }
            
            $('#problemModal').modal('show');
        });
    }

    window.deleteProblem = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this problem? It will be moved to trash.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/problems/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }

    window.restoreProblem = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to restore this problem?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/problems/${id}/restore`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }

    window.forceDeleteProblem = function(id) {
        Swal.fire({
            title: 'Are you absolutely sure?',
            text: "This will permanently delete the problem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete permanently!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/problems/${id}/force-delete`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
