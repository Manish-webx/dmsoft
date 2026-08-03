@extends('layouts.dashboard')
@section('title', 'Manage Prescription Templates')
@section('content')

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-brand">Prescription Templates</h4>
        <a href="{{ route('prescription-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Template
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover" id="templatesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Template Name</th>
                    <th>Created At</th>
                    <th width="200px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated via AJAX -->
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let table = $('#templatesTable').DataTable({
        processing: true,
        ajax: "{{ route('prescription-templates.index') }}",
        columns: [
            { data: 'id' },
            { data: 'name' },
            { 
                data: 'created_at',
                render: function(data) {
                    let d = new Date(data);
                    return d.toLocaleDateString();
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <a href="/prescription-templates/${row.id}/edit" class="btn btn-sm btn-info" title="Edit Template"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-secondary duplicate-btn" data-id="${row.id}" title="Duplicate Template"><i class="fas fa-copy"></i></button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}" title="Delete Template"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/prescription-templates/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Failed to delete template.');
                    }
                });
            }
        });
    });

    $(document).on('click', '.duplicate-btn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Duplicate Template?',
            text: "This will create a copy of the template.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, duplicate!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/prescription-templates/${id}/duplicate`,
                    type: 'POST',
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Failed to duplicate template.');
                    }
                });
            }
        });
    });
});
</script>
@endpush
