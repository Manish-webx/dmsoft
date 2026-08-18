@extends('layouts.dashboard')
@section('title', 'Edit Prescription #' . $prescription->id)
@section('content')

    <form id="prescriptionForm">
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-end align-items-center">
                <div style="width: 300px;">
                    <select id="loadTemplateSelect" class="form-select select2">
                        <option value="">-- Load from Template --</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Patient Details -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <i class="fas fa-user-injured text-primary"></i> Patient Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Select Patient *</label>
                                <select name="patient_id" class="form-select select2 w-100" required>
                                    <option value="">Search Patient...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ $prescription->patient_id == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->patient_code }} - {{ $patient->name }} ({{ $patient->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Next Follow-up Date</label>
                                <input type="date" name="next_followup_date" class="form-control"
                                    value="{{ $prescription->next_followup_date ? $prescription->next_followup_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Diagnosis</label>
                                <select name="problem_id" id="problemSelect" class="form-select select2 w-100">
                                    <option value="">None</option>
                                    @foreach($problems as $problem)
                                        <option value="{{ $problem->id }}"
                                            data-links="{{ json_encode($problem->youtube_links ?? []) }}" {{ $prescription->problem_id == $problem->id ? 'selected' : '' }}>
                                            {{ $problem->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>YouTube Links</label>
                                <select name="youtube_links[]" id="youtubeLinkSelect" class="form-select select2 w-100"
                                    multiple="multiple" data-saved="{{ json_encode($prescription->youtube_links ?? []) }}">
                                    <!-- Options will be populated via JS -->
                                </select>
                                <small class="text-muted">Select from problem or type a custom link and press enter.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>History of Present Illness (HOPI)</label>
                                <textarea name="hopi" class="form-control" rows="3">{{ $prescription->hopi }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Additional Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $prescription->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicine Selection -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-pills text-success"></i> Medicines</span>
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
                                @foreach($prescription->items as $index => $item)
                                    <tr id="row-{{ $index }}">
                                        <td>
                                            <select name="medicines[{{ $index }}]" class="form-select select2-med select2-primary w-100"
                                                required>
                                                <option value="">Search...</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}" data-price="{{ $med->price }}"
                                                        data-stock="{{ $med->stock }}" {{ $item->medicine_id == $med->id ? 'selected' : '' }}>
                                                        {{ $med->name }} (Stock: {{ $med->stock }}) - ₹{{ $med->price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="alternate_medicines[{{ $index }}]"
                                                class="form-select select2-med select2-alt w-100">
                                                <option value="">None</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}" {{ $item->alternate_medicine_id == $med->id ? 'selected' : '' }}>
                                                        {{ $med->name }}
                                                    </option>
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
                                            <textarea name="medicine_notes[{{ $index }}]" class="form-control form-control-sm" rows="1"
                                                placeholder="e.g. Take with warm water">{{ $item->medicine_note }}</textarea>
                                        </td>
                                        <td>
                                            <input type="number" name="quantities[{{ $index }}]"
                                                class="form-control form-control-sm qty-input" min="1" required
                                                placeholder="Qty" value="{{ $item->quantity }}">
                                            <input type="hidden" name="timings[{{ $index }}]" value="{{ $item->timing }}">
                                            <input type="hidden" name="instructions[{{ $index }}]" value="{{ $item->instructions }}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-btn"><i
                                                    class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center p-4 text-muted" id="emptyState"
                            style="{{ $prescription->items->count() > 0 ? 'display:none;' : '' }}">
                            No medicines added yet. Click "Add Item" to prescribe medicine.
                        </div>
                    </div>
                </div>

                <!-- Revenue Section -->
                <div class="card mb-4" id="revenueSection"
                    style="{{ $prescription->items->count() > 0 ? '' : 'display:none;' }}">
                    <div class="card-header bg-light">
                        <i class="fas fa-file-invoice-dollar text-primary"></i> Revenue Summary
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label>Subtotal (Auto calculated)</label>
                                <input type="text" id="subtotal" class="form-control" readonly
                                    value="{{ $prescription->revenue ? $prescription->revenue->subtotal : 0 }}">
                            </div>
                            <div class="col-md-2">
                                <label>Discount Type</label>
                                <select name="discount_type" id="discount_type" class="form-select">
                                    <option value="" {{ ($prescription->revenue && !$prescription->revenue->discount_type) ? 'selected' : '' }}>None</option>
                                    <option value="flat" {{ ($prescription->revenue && $prescription->revenue->discount_type == 'flat') ? 'selected' : '' }}>Flat</option>
                                    <option value="percentage" {{ ($prescription->revenue && $prescription->revenue->discount_type == 'percentage') ? 'selected' : '' }}>Percentage
                                        (%)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Discount Value</label>
                                <input type="number" name="discount_value" id="discount_value" class="form-control" min="0"
                                    value="{{ $prescription->revenue ? $prescription->revenue->discount_value : 0 }}">
                            </div>
                            <div class="col-md-2">
                                <label>Payment Mode</label>
                                <select name="payment_mode" class="form-select">
                                    @foreach(['Cash', 'Online', 'UPI', 'Card'] as $mode)
                                        <option value="{{ $mode }}" {{ ($prescription->revenue && $prescription->revenue->payment_mode == $mode) ? 'selected' : '' }}>{{ $mode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Final Amount</label>
                                <input type="number" name="final_amount" id="final_amount"
                                    class="form-control fw-bold text-success" readonly
                                    value="{{ $prescription->revenue ? $prescription->revenue->final_amount : 0 }}">
                            </div>
                            <div class="col-md-2 mt-2">
                                <label>Received Amount</label>
                                <input type="number" step="0.01" name="received_amount" id="received_amount"
                                    class="form-control" placeholder="Optional"
                                    value="{{ $prescription->revenue ? $prescription->revenue->received_amount : '' }}">
                            </div>
                            <div class="col-md-2 mt-2">
                                <label>Due Amount</label>
                                <input type="number" step="0.01" id="due_amount" class="form-control text-danger fw-bold"
                                    value="{{ $prescription->revenue ? $prescription->revenue->due_amount : 0 }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="button" class="btn btn-outline-primary me-2" id="saveTemplateBtn">
                            <i class="fas fa-save"></i> Save as Template
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">
                            <i class="fas fa-check"></i> Update Prescription
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- Save Template Modal -->
    <div class="modal fade" id="saveTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Save as Prescription Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Template Name *</label>
                        <input type="text" id="templateNameInput" class="form-control" placeholder="e.g., Fever, Diabetes Follow-up" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSaveTemplateBtn">Save Template</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
            $('.select2-med').select2({
                theme: 'bootstrap-5'
            });
            $('.select2-timing').select2({
                theme: 'bootstrap-5',
                tags: true
            });
            $('.select2-custom').select2({
                theme: 'bootstrap-5',
                tags: true
            });

            let rowCount = {{ $prescription->items->count() }};

            function calculateRevenue() {
                let subtotal = 0;
                $('.qty-input').each(function () {
                    let qty = parseFloat($(this).val()) || 0;
                    let tr = $(this).closest('tr');
                    let price = parseFloat(tr.find('.select2-primary option:selected').data('price')) || 0;
                    subtotal += (qty * price);
                });
                $('#subtotal').val(subtotal.toFixed(2));

                let discountType = $('#discount_type').val();
                let discountVal = parseFloat($('#discount_value').val()) || 0;
                let finalAmt = subtotal;

                if (discountType === 'flat') {
                    finalAmt -= discountVal;
                } else if (discountType === 'percentage') {
                    finalAmt -= (subtotal * (discountVal / 100));
                }

                if (finalAmt < 0) finalAmt = 0;
                $('#subtotal').val(subtotal.toFixed(2));
                $('#final_amount').val(finalAmt.toFixed(2));

                let received = $('#received_amount').val();
                if (received === '' || received === null) {
                    $('#due_amount').val('0.00');
                } else {
                    let due = finalAmt - parseFloat(received);
                    $('#due_amount').val(due.toFixed(2));
                }
            }

            // Fetch and populate templates dropdown
            function loadTemplatesDropdown() {
                $.ajax({
                    url: "{{ route('prescription-templates.all') }}",
                    type: "GET",
                    success: function(response) {
                        let options = '<option value="">-- Load from Template --</option>';
                        response.forEach(function(template) {
                            options += `<option value="${template.id}">${template.name}</option>`;
                        });
                        $('#loadTemplateSelect').html(options).trigger('change.select2');
                    }
                });
            }
            loadTemplatesDropdown();

            $('#addMedicineBtn').click(function () {
                rowCount++;
                $('#emptyState').hide();
                $('#revenueSection').show();

                let row = `
                    <tr id="row-${rowCount}">
                        <td>
                            <select name="medicines[${rowCount}]" class="form-select select2-med select2-primary w-100" required>
                                <option value="">Search...</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}" data-price="{{ $med->price }}" data-stock="{{ $med->stock }}">{{ $med->name }} (Stock: {{ $med->stock }}) - ₹{{ $med->price }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="alternate_medicines[${rowCount}]" class="form-select select2-med select2-alt w-100">
                                <option value="">None</option>
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

                $(`#row-${rowCount} .select2-med`).select2({
                    theme: 'bootstrap-5'
                });
                
                $(`#row-${rowCount} .select2-timing`).select2({
                    theme: 'bootstrap-5',
                    tags: true
                });

                $(`#row-${rowCount} .select2-custom`).select2({
                    theme: 'bootstrap-5',
                    tags: true
                });
            });

            $(document).on('click', '.remove-btn', function () {
                $(this).closest('tr').remove();
                calculateRevenue();
                if ($('#medicineList tr').length === 0) {
                    $('#emptyState').show();
                    $('#revenueSection').hide();
                }
            });

            $(document).on('input', '.qty-input', calculateRevenue);
            $(document).on('change', '.select2-primary', calculateRevenue);
            $('#discount_type, #discount_value, #received_amount').on('input change', calculateRevenue);

            // Problem & YouTube Link Logic
            $('#youtubeLinkSelect').select2({
                theme: 'bootstrap-5',
                tags: true,
                placeholder: "Select or type links...",
                allowClear: true
            });

            let isInitialLoad = true;

            function populateYoutubeLinks() {
                let selectedOpt = $('#problemSelect').find('option:selected');
                let links = selectedOpt.data('links') || [];

                let html = '';
                if (typeof links === 'string') {
                    try { links = JSON.parse(links); } catch (e) { links = []; }
                }

                let currentSelection = [];
                if (isInitialLoad) {
                    currentSelection = $('#youtubeLinkSelect').data('saved') || [];
                    if (typeof currentSelection === 'string') {
                        try { currentSelection = JSON.parse(currentSelection); } catch (e) { currentSelection = []; }
                    }
                } else {
                    currentSelection = $('#youtubeLinkSelect').val() || [];
                }
                if (!Array.isArray(currentSelection)) currentSelection = [];

                if (Array.isArray(links)) {
                    links.forEach(function (link) {
                        if (link) {
                            html += `<option value="${link}">${link}</option>`;
                        }
                    });
                }

                currentSelection.forEach(function (val) {
                    if (html.indexOf(`value="${val}"`) === -1) {
                        html += `<option value="${val}" selected>${val}</option>`;
                    }
                });

                $('#youtubeLinkSelect').html(html);
                $('#youtubeLinkSelect').val(currentSelection).trigger('change');
                isInitialLoad = false;
            }

            populateYoutubeLinks();

            $('#problemSelect').change(function () {
                populateYoutubeLinks();
            });

            // Handle Load Template
            $('#loadTemplateSelect').change(function() {
                let templateId = $(this).val();
                if(!templateId) return;

                $.ajax({
                    url: `/prescription-templates/${templateId}`,
                    type: "GET",
                    success: function(template) {
                        if(template.problem_id) {
                            $('#problemSelect').val(template.problem_id).trigger('change');
                        } else {
                            $('#problemSelect').val('').trigger('change');
                        }

                        let links = template.youtube_links || [];
                        let html = '';
                        links.forEach(function (link) {
                            html += `<option value="${link}" selected>${link}</option>`;
                        });
                        $('#youtubeLinkSelect').html(html).trigger('change');

                        $('textarea[name="hopi"]').val(template.hopi || '');
                        $('textarea[name="notes"]').val(template.notes || '');

                        $('#medicineList').empty();
                        
                        if(template.items && template.items.length > 0) {
                            $('#emptyState').hide();
                            $('#revenueSection').show();

                            template.items.forEach(function(item) {
                                rowCount++;
                                
                                let row = `
                                    <tr id="row-${rowCount}">
                                        <td>
                                            <select name="medicines[${rowCount}]" class="form-select select2-med select2-primary w-100" required>
                                                <option value="">Search...</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}" data-price="{{ $med->price }}" data-stock="{{ $med->stock }}">{{ $med->name }} (Stock: {{ $med->stock }}) - ₹{{ $med->price }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="alternate_medicines[${rowCount}]" class="form-select select2-med select2-alt w-100">
                                                <option value="">None</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}">{{ $med->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="times[${rowCount}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                                <option value="Morning" ${item.time && item.time.includes('Morning') ? 'selected' : ''}>Morning</option>
                                                <option value="Afternoon" ${item.time && item.time.includes('Afternoon') ? 'selected' : ''}>Afternoon</option>
                                                <option value="Evening" ${item.time && item.time.includes('Evening') ? 'selected' : ''}>Evening</option>
                                                <option value="Night" ${item.time && item.time.includes('Night') ? 'selected' : ''}>Night</option>
                                                ${item.time && item.time.split(',').find(t => !['Morning', 'Afternoon', 'Evening', 'Night'].includes(t.trim()) && t.trim() !== '') ? item.time.split(',').filter(t => !['Morning', 'Afternoon', 'Evening', 'Night'].includes(t.trim()) && t.trim() !== '').map(t => `<option value="${t.trim()}" selected>${t.trim()}</option>`).join('') : ''}
                                            </select>
                                        </td>
                                        <td>
                                            <select name="whens[${rowCount}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                                <option value="Before Food" ${item.when && item.when.includes('Before Food') ? 'selected' : ''}>Before Food</option>
                                                <option value="After Food" ${item.when && item.when.includes('After Food') ? 'selected' : ''}>After Food</option>
                                                <option value="Empty stomach" ${item.when && item.when.includes('Empty stomach') ? 'selected' : ''}>Empty stomach</option>
                                                <option value="With Food" ${item.when && item.when.includes('With Food') ? 'selected' : ''}>With Food</option>
                                                <option value="At bedtime" ${item.when && item.when.includes('At bedtime') ? 'selected' : ''}>At bedtime</option>
                                                <option value="After bath" ${item.when && item.when.includes('After bath') ? 'selected' : ''}>After bath</option>
                                                <option value="After Facewash" ${item.when && item.when.includes('After Facewash') ? 'selected' : ''}>After Facewash</option>
                                                <option value="Overnight" ${item.when && item.when.includes('Overnight') ? 'selected' : ''}>Overnight</option>
                                                <option value="After mouthwash" ${item.when && item.when.includes('After mouthwash') ? 'selected' : ''}>After mouthwash</option>
                                                ${item.when && item.when.split(',').find(t => !['Before Food', 'After Food', 'Empty stomach', 'With Food', 'At bedtime', 'After bath', 'After Facewash', 'Overnight', 'After mouthwash'].includes(t.trim()) && t.trim() !== '') ? item.when.split(',').filter(t => !['Before Food', 'After Food', 'Empty stomach', 'With Food', 'At bedtime', 'After bath', 'After Facewash', 'Overnight', 'After mouthwash'].includes(t.trim()) && t.trim() !== '').map(t => `<option value="${t.trim()}" selected>${t.trim()}</option>`).join('') : ''}
                                            </select>
                                        </td>
                                        <td>
                                            <select name="frequencies[${rowCount}][]" class="form-select select2-custom form-select-sm w-100" multiple="multiple" data-placeholder="Select...">
                                                <option value="Daily" ${item.frequency && item.frequency.includes('Daily') ? 'selected' : ''}>Daily</option>
                                                <option value="Once weekly" ${item.frequency && item.frequency.includes('Once weekly') ? 'selected' : ''}>Once weekly</option>
                                                <option value="Twice weekly" ${item.frequency && item.frequency.includes('Twice weekly') ? 'selected' : ''}>Twice weekly</option>
                                                <option value="Thrice weekly" ${item.frequency && item.frequency.includes('Thrice weekly') ? 'selected' : ''}>Thrice weekly</option>
                                                <option value="4 times weekly" ${item.frequency && item.frequency.includes('4 times weekly') ? 'selected' : ''}>4 times weekly</option>
                                                <option value="5 times daily" ${item.frequency && item.frequency.includes('5 times daily') ? 'selected' : ''}>5 times daily</option>
                                                <option value="Weekly" ${item.frequency && item.frequency.includes('Weekly') ? 'selected' : ''}>Weekly</option>
                                                <option value="Fortnightly" ${item.frequency && item.frequency.includes('Fortnightly') ? 'selected' : ''}>Fortnightly</option>
                                                ${item.frequency && item.frequency.split(',').find(t => !['Daily', 'Once weekly', 'Twice weekly', 'Thrice weekly', '4 times weekly', '5 times daily', 'Weekly', 'Fortnightly'].includes(t.trim()) && t.trim() !== '') ? item.frequency.split(',').filter(t => !['Daily', 'Once weekly', 'Twice weekly', 'Thrice weekly', '4 times weekly', '5 times daily', 'Weekly', 'Fortnightly'].includes(t.trim()) && t.trim() !== '').map(t => `<option value="${t.trim()}" selected>${t.trim()}</option>`).join('') : ''}
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <input type="number" name="durations[${rowCount}]" class="form-control form-control-sm" placeholder="1" min="1" value="${item.duration || ''}">
                                                <select name="duration_types[${rowCount}]" class="form-select form-select-sm" style="min-width: 70px;">
                                                    <option value="Days" ${item.duration_type == 'Days' ? 'selected' : ''}>Days</option>
                                                    <option value="Weeks" ${item.duration_type == 'Weeks' ? 'selected' : ''}>Weeks</option>
                                                    <option value="Months" ${item.duration_type == 'Months' ? 'selected' : ''}>Months</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td><textarea name="medicine_notes[${rowCount}]" class="form-control form-control-sm" rows="1">${item.medicine_note || ''}</textarea></td>
                                        <td>
                                            <input type="number" name="quantities[${rowCount}]" class="form-control form-control-sm qty-input" min="1" required placeholder="Qty" value="${item.quantity}">
                                            <input type="hidden" name="timings[${rowCount}]" value="${item.timing || ''}">
                                            <input type="hidden" name="instructions[${rowCount}]" value="${item.instructions || ''}">
                                        </td>
                                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-btn"><i class="fas fa-times"></i></button></td>
                                    </tr>
                                `;
                                
                                $('#medicineList').append(row);
                                
                                $(`#row-${rowCount} select[name="medicines[${rowCount}]"]`).val(item.medicine_id).select2({ theme: 'bootstrap-5' });
                                $(`#row-${rowCount} select[name="alternate_medicines[${rowCount}]"]`).val(item.alternate_medicine_id || '').select2({ theme: 'bootstrap-5' });
                                $(`#row-${rowCount} .select2-timing`).select2({ theme: 'bootstrap-5', tags: true });
                                $(`#row-${rowCount} .select2-custom`).select2({ theme: 'bootstrap-5', tags: true });
                            });
                            calculateRevenue();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to load template.');
                    }
                });
            });

            // Handle Save as Template
            let saveTemplateModal = new bootstrap.Modal(document.getElementById('saveTemplateModal'));
            
            $('#saveTemplateBtn').click(function() {
                $('#templateNameInput').val('');
                saveTemplateModal.show();
            });

            $('#confirmSaveTemplateBtn').click(function() {
                let tName = $('#templateNameInput').val();
                if(!tName) {
                    toastr.warning('Please enter a template name.');
                    return;
                }
                
                let formData = $('#prescriptionForm').serializeArray();
                formData.push({name: 'template_name', value: tName});
                
                // Remove the _method PUT for template save
                formData = formData.filter(item => item.name !== '_method');

                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                let btn = $(this);

                $.ajax({
                    url: "{{ route('prescription-templates.store') }}",
                    type: "POST",
                    data: $.param(formData),
                    success: function(response) {
                        toastr.success(response.success);
                        saveTemplateModal.hide();
                        btn.prop('disabled', false).html('Save Template');
                        loadTemplatesDropdown();
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('Save Template');
                        if (xhr.status === 422 && xhr.responseJSON.error) {
                            toastr.error(xhr.responseJSON.error);
                        } else if (xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let key in errors) {
                                toastr.error(errors[key][0]);
                            }
                        } else {
                            toastr.error('Failed to save template.');
                        }
                    }
                });
            });

            // Form Submit
            $('#prescriptionForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: "{{ route('prescriptions.update', $prescription->id) }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.success);
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Update Prescription');
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