@extends('layouts.dashboard')
@section('title', 'Medicines Inventory')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Medicine List {{ request('trashed') ? '(Trash)' : '' }}</span>
        <div>
            @if(request('trashed'))
                <a href="{{ route('medicines.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to List</a>
            @else
                <a href="{{ route('medicines.index', ['trashed' => 1]) }}" class="btn btn-warning btn-sm me-2"><i class="fas fa-trash"></i> View Trash</a>
                <button class="btn btn-primary btn-sm" onclick="openModal()">
                    <i class="fas fa-plus"></i> Add Medicine
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <table id="medicinesTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Items/Strip</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Medicine Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="medicineForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="medicine_id" name="id">
                    <div class="mb-3">
                        <label>Medicine Name *</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Unit (e.g., Tablets, ml, Bottle)</label>
                        <input type="text" name="unit" id="unit" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Items per Strip (for Tablets/Capsules)</label>
                        <input type="number" name="items_per_strip" id="items_per_strip" class="form-control" min="1">
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="status" id="status" class="form-check-input" value="1" checked>
                        <label class="form-check-label">Active</label>
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

<!-- Add Stock Modal -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="stockForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="stock_medicine_id" name="medicine_id">
                    <div class="mb-3">
                        <label>Quantity to Add *</label>
                        <input type="number" name="quantity" class="form-control" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100" id="saveStockBtn">Add Stock</button>
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
        table = $('#medicinesTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('medicines.index') }}",
                data: function(d) {
                    d.trashed = "{{ request('trashed', 0) }}";
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { 
                    data: 'stock',
                    render: function(data) {
                        return data < 10 ? `<span class="badge bg-danger">${data}</span>` : `<span class="badge bg-success">${data}</span>`;
                    }
                },
                { data: 'unit' },
                { 
                    data: 'items_per_strip',
                    render: function(data) {
                        return data ? data : '-';
                    }
                },
                { data: 'price' },
                { 
                    data: 'status',
                    render: function(data) {
                        return data ? '<span class="badge bg-primary">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                    }
                },
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        let isTrashed = "{{ request('trashed', 0) }}";
                        if (isTrashed == "1") {
                            return `
                                <button class="btn btn-sm btn-success" onclick="restoreMedicine(${data})"><i class="fas fa-undo"></i> Restore</button>
                                <button class="btn btn-sm btn-danger" onclick="forceDeleteMedicine(${data})"><i class="fas fa-times"></i> Delete Permanently</button>
                            `;
                        } else {
                            return `
                                <button class="btn btn-sm btn-success text-white" onclick="addStock(${data})" title="Add Stock"><i class="fas fa-plus-circle"></i></button>
                                <button class="btn btn-sm btn-info text-white" onclick="editMedicine(${data})" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteMedicine(${data})" title="Delete"><i class="fas fa-trash"></i></button>
                            `;
                        }
                    }
                }
            ]
        });

        $('#medicineForm').submit(function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).text('Saving...');
            
            $.ajax({
                url: "{{ route('medicines.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#medicineModal').modal('hide');
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

        $('#stockForm').submit(function(e) {
            e.preventDefault();
            $('#saveStockBtn').prop('disabled', true).text('Adding...');
            
            $.ajax({
                url: "{{ route('medicines.add-stock') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#stockModal').modal('hide');
                    toastr.success(response.success);
                    table.ajax.reload();
                    $('#saveStockBtn').prop('disabled', false).text('Add Stock');
                },
                error: function(xhr) {
                    $('#saveStockBtn').prop('disabled', false).text('Add Stock');
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        toastr.error(errors[key][0]);
                    }
                }
            });
        });
    });

    function openModal() {
        $('#medicineForm')[0].reset();
        $('#medicine_id').val('');
        $('#status').prop('checked', true);
        $('#modalTitle').text('Add Medicine');
        $('#medicineModal').modal('show');
    }

    function editMedicine(id) {
        $.get(`/medicines/${id}/edit`, function(data) {
            $('#medicine_id').val(data.id);
            $('#name').val(data.name);
            $('#unit').val(data.unit);
            $('#items_per_strip').val(data.items_per_strip);
            $('#price').val(data.price);
            $('#status').prop('checked', data.status == 1);
            $('#modalTitle').text('Edit Medicine');
            $('#medicineModal').modal('show');
        });
    }

    function deleteMedicine(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this medicine? It will be moved to trash.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/medicines/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }

    function restoreMedicine(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to restore this medicine?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/medicines/${id}/restore`,
                    type: 'POST',
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }

    function forceDeleteMedicine(id) {
        Swal.fire({
            title: 'Are you absolutely sure?',
            text: "This will permanently delete the medicine.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete permanently!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/medicines/${id}/force-delete`,
                    type: 'DELETE',
                    success: function(response) {
                        toastr.success(response.success);
                        table.ajax.reload();
                    }
                });
            }
        });
    }

    function addStock(id) {
        $('#stockForm')[0].reset();
        $('#stock_medicine_id').val(id);
        $('#stockModal').modal('show');
    }
</script>
@endpush
