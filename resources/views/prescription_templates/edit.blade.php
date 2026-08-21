@extends('layouts.dashboard')
@section('title', 'Edit Prescription Template')
@section('content')

    <form id="templateForm">
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
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

            <div class="col-md-12">
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
                                    <th width="15%">Medicine</th>
                                    <th width="15%">Alt Medicine</th>
                                    <th width="10%">Time</th>
                                    <th width="12%">When</th>
                                    <th width="10%">Frequency</th>
                                    <th width="10%">Duration</th>
                                    <th width="15%">Medicine Note</th>
                                    <th width="8%">Qty</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="medicineList">
                                @foreach($template->items as $index => $item)
                                <tr id="row-{{ $index }}">
                                    <td>
                                        <select name="medicines[{{ $index }}]" class="form-select select2-med select2-primary w-100" required>
                                            <option value="">Search...</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" {{ $item->medicine_id == $med->id ? 'selected' : '' }}>{{ $med->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @php $selectedAlts = $item->alternate_medicine_id ? explode(',', $item->alternate_medicine_id) : []; @endphp
                                        <select name="alternate_medicines[{{ $index }}][]" class="form-select select2-med select2-alt w-100" multiple="multiple" data-placeholder="Select...">
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" {{ in_array($med->id, $selectedAlts) ? 'selected' : '' }}>{{ $med->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @php $selectedTimes = $item->time ? array_map('trim', explode(',', $item->time)) : []; @endphp
                                        <select name="times[{{ $index }}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                            <option value="Morning" {{ in_array('Morning', $selectedTimes) ? 'selected' : '' }}>Morning</option>
                                            <option value="Afternoon" {{ in_array('Afternoon', $selectedTimes) ? 'selected' : '' }}>Afternoon</option>
                                            <option value="Evening" {{ in_array('Evening', $selectedTimes) ? 'selected' : '' }}>Evening</option>
                                            <option value="Night" {{ in_array('Night', $selectedTimes) ? 'selected' : '' }}>Night</option>
                                            @foreach(array_diff($selectedTimes, ['Morning', 'Afternoon', 'Evening', 'Night']) as $custom)
                                                @if(trim($custom) !== '')
                                                    <option value="{{ $custom }}" selected>{{ $custom }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @php $selectedWhens = $item->when ? array_map('trim', explode(',', $item->when)) : []; @endphp
                                        <select name="whens[{{ $index }}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                            <option value="Before Food" {{ in_array('Before Food', $selectedWhens) ? 'selected' : '' }}>Before Food</option>
                                            <option value="After Food" {{ in_array('After Food', $selectedWhens) ? 'selected' : '' }}>After Food</option>
                                            <option value="Empty stomach" {{ in_array('Empty stomach', $selectedWhens) ? 'selected' : '' }}>Empty stomach</option>
                                            <option value="With Food" {{ in_array('With Food', $selectedWhens) ? 'selected' : '' }}>With Food</option>
                                            <option value="At bedtime" {{ in_array('At bedtime', $selectedWhens) ? 'selected' : '' }}>At bedtime</option>
                                            <option value="After bath" {{ in_array('After bath', $selectedWhens) ? 'selected' : '' }}>After bath</option>
                                            <option value="After Facewash" {{ in_array('After Facewash', $selectedWhens) ? 'selected' : '' }}>After Facewash</option>
                                            <option value="Overnight" {{ in_array('Overnight', $selectedWhens) ? 'selected' : '' }}>Overnight</option>
                                            <option value="After mouthwash" {{ in_array('After mouthwash', $selectedWhens) ? 'selected' : '' }}>After mouthwash</option>
                                            @foreach(array_diff($selectedWhens, ['Before Food', 'After Food', 'Empty stomach', 'With Food', 'At bedtime', 'After bath', 'After Facewash', 'Overnight', 'After mouthwash']) as $custom)
                                                @if(trim($custom) !== '')
                                                    <option value="{{ $custom }}" selected>{{ $custom }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @php $selectedFreqs = $item->frequency ? array_map('trim', explode(',', $item->frequency)) : []; @endphp
                                        <select name="frequencies[{{ $index }}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                            <option value="Daily" {{ in_array('Daily', $selectedFreqs) ? 'selected' : '' }}>Daily</option>
                                            <option value="Once weekly" {{ in_array('Once weekly', $selectedFreqs) ? 'selected' : '' }}>Once weekly</option>
                                            <option value="Twice weekly" {{ in_array('Twice weekly', $selectedFreqs) ? 'selected' : '' }}>Twice weekly</option>
                                            <option value="Thrice weekly" {{ in_array('Thrice weekly', $selectedFreqs) ? 'selected' : '' }}>Thrice weekly</option>
                                            <option value="4 times weekly" {{ in_array('4 times weekly', $selectedFreqs) ? 'selected' : '' }}>4 times weekly</option>
                                            <option value="5 times daily" {{ in_array('5 times daily', $selectedFreqs) ? 'selected' : '' }}>5 times daily</option>
                                            <option value="Weekly" {{ in_array('Weekly', $selectedFreqs) ? 'selected' : '' }}>Weekly</option>
                                            <option value="Fortnightly" {{ in_array('Fortnightly', $selectedFreqs) ? 'selected' : '' }}>Fortnightly</option>
                                            @foreach(array_diff($selectedFreqs, ['Daily', 'Once weekly', 'Twice weekly', 'Thrice weekly', '4 times weekly', '5 times daily', 'Weekly', 'Fortnightly']) as $custom)
                                                @if(trim($custom) !== '')
                                                    <option value="{{ $custom }}" selected>{{ $custom }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <input type="number" name="durations[{{ $index }}]" class="form-control form-control-sm" placeholder="1" min="1" value="{{ $item->duration }}">
                                            <select name="duration_types[{{ $index }}]" class="form-select form-select-sm" style="min-width: 70px;">
                                                <option value="Days" {{ $item->duration_type == 'Days' ? 'selected' : '' }}>Days</option>
                                                <option value="Weeks" {{ $item->duration_type == 'Weeks' ? 'selected' : '' }}>Weeks</option>
                                                <option value="Months" {{ $item->duration_type == 'Months' ? 'selected' : '' }}>Months</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea name="medicine_notes[{{ $index }}]" class="form-control form-control-sm" rows="1" placeholder="e.g. Take with warm water">{{ $item->medicine_note }}</textarea>
                                    </td>
                                    <td>
                                        <input type="number" name="quantities[{{ $index }}]" class="form-control form-control-sm qty-input" min="1" value="{{ $item->quantity }}" required placeholder="Qty">
                                        <input type="hidden" name="timings[{{ $index }}]" value="{{ $item->timing }}">
                                        <input type="hidden" name="instructions[{{ $index }}]" value="{{ $item->instructions }}">
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
        <select class="form-select alt-med" style="width: 100%;" multiple="multiple" data-placeholder="Select...">
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
            $('.select2-custom').select2({ theme: 'bootstrap-5', tags: true });

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
                            <select name="medicines[${rowCount}]" class="form-select select2-med select2-primary w-100" required>
                                <option value="">Search...</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}">{{ $med->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="alternate_medicines[${rowCount}][]" class="form-select select2-med select2-alt w-100" multiple="multiple" data-placeholder="Select...">
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}">{{ $med->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="times[${rowCount}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                <option value="Morning">Morning</option>
                                <option value="Afternoon">Afternoon</option>
                                <option value="Evening">Evening</option>
                                <option value="Night">Night</option>
                            </select>
                        </td>
                        <td>
                            <select name="whens[${rowCount}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                <option value="Before Food">Before Food</option>
                                <option value="After Food">After Food</option>
                                <option value="Empty stomach">Empty stomach</option>
                                <option value="With Food">With Food</option>
                                <option value="At bedtime">At bedtime</option>
                                <option value="After bath">After bath</option>
                                <option value="After Facewash">After Facewash</option>
                                <option value="Overnight">Overnight</option>
                                <option value="After mouthwash">After mouthwash</option>
                            </select>
                        </td>
                        <td>
                            <select name="frequencies[${rowCount}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                <option value="Daily">Daily</option>
                                <option value="Once weekly">Once weekly</option>
                                <option value="Twice weekly">Twice weekly</option>
                                <option value="Thrice weekly">Thrice weekly</option>
                                <option value="4 times weekly">4 times weekly</option>
                                <option value="5 times daily">5 times daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Fortnightly">Fortnightly</option>
                            </select>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <input type="number" name="durations[${rowCount}]" class="form-control form-control-sm" placeholder="1" min="1">
                                <select name="duration_types[${rowCount}]" class="form-select form-select-sm" style="min-width: 70px;">
                                    <option value="Days">Days</option>
                                    <option value="Weeks">Weeks</option>
                                    <option value="Months">Months</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <textarea name="medicine_notes[${rowCount}]" class="form-control form-control-sm" rows="1" placeholder="e.g. Take with warm water"></textarea>
                        </td>
                        <td>
                            <input type="number" name="quantities[${rowCount}]" class="form-control form-control-sm qty-input" min="1" required placeholder="Qty">
                            <input type="hidden" name="timings[${rowCount}]" value="">
                            <input type="hidden" name="instructions[${rowCount}]" value="">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-btn"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                `;

                $('#medicineList').append(row);

                $(`#row-${rowCount} .select2-med`).select2({ theme: 'bootstrap-5' });
                $(`#row-${rowCount} .select2-timing`).select2({ theme: 'bootstrap-5', tags: true });
                $(`#row-${rowCount} .select2-custom`).select2({ theme: 'bootstrap-5', tags: true });
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
