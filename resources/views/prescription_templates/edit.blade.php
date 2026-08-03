@extends('layouts.dashboard')
@section('title', 'Edit Prescription Template')
@section('content')

    <form id="templateForm">
        @method('PUT')
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <i class="fas fa-file-alt text-primary"></i> Template Details
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Template Name *</label>
                            <input type="text" name="template_name" class="form-control" value="{{ $template->name }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-pills text-success"></i> Template Medicines</span>
                        <button type="button" class="btn btn-sm btn-success" id="addMedicineBtn">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0" id="medicineTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%">Primary Medicine</th>
                                    <th width="20%">Alt Medicine</th>
                                    <th width="10%">Qty</th>
                                    <th width="12%">Timing</th>
                                    <th width="10%">Duration</th>
                                    <th width="23%">Medicine Note</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="medicineList">
                                @foreach($template->items as $index => $item)
                                <tr id="row-{{ $index }}">
                                    <td>
                                        <select name="medicines[]" class="form-select select2-med select2-primary w-100" required>
                                            <option value="">Search...</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" {{ $item->medicine_id == $med->id ? 'selected' : '' }}>{{ $med->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="alternate_medicines[]" class="form-select select2-med select2-alt w-100">
                                            <option value="">None</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" {{ $item->alternate_medicine_id == $med->id ? 'selected' : '' }}>{{ $med->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantities[]" class="form-control form-control-sm qty-input" min="1" value="{{ $item->quantity }}" required placeholder="Qty">
                                    </td>
                                    <td>
                                        <select name="timings[]" class="form-select form-select-sm select2-timing w-100">
                                            <option value="Before food" {{ $item->timing == 'Before food' ? 'selected' : '' }}>Before food</option>
                                            <option value="After food" {{ $item->timing == 'After food' ? 'selected' : '' }}>After food</option>
                                            <option value="Morning" {{ $item->timing == 'Morning' ? 'selected' : '' }}>Morning</option>
                                            <option value="Afternoon" {{ $item->timing == 'Afternoon' ? 'selected' : '' }}>Afternoon</option>
                                            <option value="Night" {{ $item->timing == 'Night' ? 'selected' : '' }}>Night</option>
                                            <option value="Empty stomach" {{ $item->timing == 'Empty stomach' ? 'selected' : '' }}>Empty stomach</option>
                                            <option value="SOS" {{ $item->timing == 'SOS' ? 'selected' : '' }}>SOS</option>
                                        </select>
                                        <input type="hidden" name="instructions[]" value="{{ $item->instructions }}">
                                    </td>
                                    <td>
                                        <input type="text" name="durations[]" class="form-control form-control-sm" value="{{ $item->duration }}" placeholder="e.g. 5 days">
                                    </td>
                                    <td>
                                        <textarea name="medicine_notes[]" class="form-control form-control-sm" rows="1" placeholder="e.g. Take with warm water">{{ $item->medicine_note }}</textarea>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-btn"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center p-4 text-muted" id="emptyState" style="{{ count($template->items) > 0 ? 'display:none;' : '' }}">
                            No medicines added yet. Click "Add Item".
                        </div>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="fas fa-save"></i> Update Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Templates for JS -->
    <div class="d-none" id="medicineTemplate">
        <select class="form-select primary-med" style="width: 100%;">
            <option value="">Select Medicine...</option>
            @foreach($medicines as $med)
                <option value="{{ $med->id }}">{{ $med->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-none" id="altMedicineTemplate">
        <select class="form-select alt-med" style="width: 100%;">
            <option value="">None</option>
            @foreach($medicines as $med)
                <option value="{{ $med->id }}">{{ $med->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-none" id="timingTemplate">
        <select class="form-select form-select-sm timing-select">
            <option value="Before food">Before food</option>
            <option value="After food">After food</option>
            <option value="Morning">Morning</option>
            <option value="Afternoon">Afternoon</option>
            <option value="Night">Night</option>
            <option value="Empty stomach">Empty stomach</option>
            <option value="SOS">SOS</option>
        </select>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({ theme: 'bootstrap-5' });
            $('.select2-med').select2({ theme: 'bootstrap-5' });
            $('.select2-timing').select2({ theme: 'bootstrap-5', tags: true });

            let rowCount = {{ count($template->items) }};

            $('#addMedicineBtn').click(function () {
                rowCount++;
                $('#emptyState').hide();

                let medHtml = $('#medicineTemplate').html();
                medHtml = medHtml.replace('primary-med', 'select2-med select2-primary');

                let altMedHtml = $('#altMedicineTemplate').html();
                altMedHtml = altMedHtml.replace('alt-med', 'select2-med select2-alt');

                let timingHtml = $('#timingTemplate').html();
                timingHtml = timingHtml.replace('timing-select', 'timing-select-' + rowCount);

                let row = `
                    <tr id="row-${rowCount}">
                        <td>
                            <select name="medicines[]" class="form-select select2-med select2-primary w-100" required>
                                <option value="">Search...</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}">{{ $med->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="alternate_medicines[]" class="form-select select2-med select2-alt w-100">
                                <option value="">None</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}">{{ $med->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantities[]" class="form-control form-control-sm qty-input" min="1" required placeholder="Qty">
                        </td>
                        <td>
                            <select name="timings[]" class="form-select form-select-sm select2-timing w-100">
                                <option value="Before food">Before food</option>
                                <option value="After food">After food</option>
                                <option value="Morning">Morning</option>
                                <option value="Afternoon">Afternoon</option>
                                <option value="Night">Night</option>
                                <option value="Empty stomach">Empty stomach</option>
                                <option value="SOS">SOS</option>
                            </select>
                            <input type="hidden" name="instructions[]" value="">
                        </td>
                        <td>
                            <input type="text" name="durations[]" class="form-control form-control-sm" placeholder="e.g. 5 days">
                        </td>
                        <td>
                            <textarea name="medicine_notes[]" class="form-control form-control-sm" rows="1" placeholder="e.g. Take with warm water"></textarea>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-btn"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                `;

                $('#medicineList').append(row);

                $(`#row-${rowCount} .select2-med`).select2({ theme: 'bootstrap-5' });
                $(`#row-${rowCount} .select2-timing`).select2({ theme: 'bootstrap-5', tags: true });
            });

            $(document).on('click', '.remove-btn', function () {
                $(this).closest('tr').remove();
                if ($('#medicineList tr').length === 0) {
                    $('#emptyState').show();
                }
            });

            $('#templateForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: "{{ route('prescription-templates.update', $template->id) }}",
                    type: "POST", // method override
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.success);
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Update Template');
                        if (xhr.status === 422 && xhr.responseJSON.error) {
                            toastr.error(xhr.responseJSON.error);
                        } else if (xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let key in errors) {
                                toastr.error(errors[key][0]);
                            }
                        } else {
                            toastr.error('Something went wrong.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
