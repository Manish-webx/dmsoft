@extends('layouts.dashboard')
@section('title', 'Write Prescription')
@section('content')

    <form id="prescriptionForm">
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
                                        <option value="{{ $patient->id }}">{{ $patient->patient_code }} - {{ $patient->name }}
                                            ({{ $patient->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Next Follow-up Date</label>
                                <input type="date" name="next_followup_date" class="form-control">
                                <small class="text-muted">Will automatically create a Follow-up appointment.</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Diagnosis</label>
                                <select name="problem_id" id="problemSelect" class="form-select select2 w-100">
                                    <option value="">None</option>
                                    @foreach($problems as $problem)
                                        <option value="{{ $problem->id }}"
                                            data-links="{{ json_encode($problem->youtube_links ?? []) }}">{{ $problem->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>YouTube Links</label>
                                <select name="youtube_links[]" id="youtubeLinkSelect" class="form-select select2 w-100"
                                    multiple="multiple">
                                    <!-- Options will be populated via JS -->
                                </select>
                                <small class="text-muted">Select from problem or type a custom link and press enter.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>History of Present Illness (HOPI)</label>
                                <textarea name="hopi" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Additional Notes</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
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
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                        <div class="text-center p-4 text-muted" id="emptyState">
                            No medicines added yet. Click "Add Item" to prescribe medicine.
                        </div>
                    </div>
                </div>

                <!-- Revenue Section -->
                <div class="card mb-4" id="revenueSection" style="display: none;">
                    <div class="card-header bg-light">
                        <i class="fas fa-file-invoice-dollar text-primary"></i> Revenue Summary
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label>Subtotal (Auto calculated)</label>
                                <input type="text" id="subtotal" class="form-control" readonly value="0">
                            </div>
                            <div class="col-md-2">
                                <label>Discount Type</label>
                                <select name="discount_type" id="discount_type" class="form-select">
                                    <option value="">None</option>
                                    <option value="flat">Flat</option>
                                    <option value="percentage">Percentage (%)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Discount Value</label>
                                <input type="number" name="discount_value" id="discount_value" class="form-control" min="0"
                                    value="0">
                            </div>
                            <div class="col-md-2">
                                <label>Payment Mode</label>
                                <select name="payment_mode" class="form-select">
                                    <option value="Cash">Cash</option>
                                    <option value="Online">Online</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Card">Card</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Final Amount</label>
                                <input type="number" name="final_amount" id="final_amount"
                                    class="form-control fw-bold text-success" value="0" readonly>
                            </div>
                            <div class="col-md-2 mt-2">
                                <label>Received Amount</label>
                                <input type="number" step="0.01" name="received_amount" id="received_amount"
                                    class="form-control" placeholder="Optional">
                            </div>
                            <div class="col-md-2 mt-2">
                                <label>Due Amount</label>
                                <input type="number" step="0.01" id="due_amount" class="form-control text-danger fw-bold"
                                    value="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="button" class="btn btn-outline-primary me-2" id="saveTemplateBtn">
                            <i class="fas fa-save"></i> Save as Template
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">
                            <i class="fas fa-check"></i> Save Prescription
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <div class="d-none" id="medicineTemplate">
        <select class="form-select primary-med" style="width: 100%;">
            <option value="">Select Medicine...</option>
            @foreach($medicines as $med)
                @php
                    $stripQty = ($med->items_per_strip && (int)$med->items_per_strip > 1) ? (int)$med->items_per_strip : 1;
                    $unitPrice = $stripQty > 1 ? round((float)$med->price / $stripQty, 2) : (float)$med->price;
                    $label = $med->name . ' (Stock: ' . $med->stock . ')';
                    if ($stripQty > 1) {
                        $label .= ' - ₹' . number_format((float)$med->price, 2) . '/strip (₹' . number_format($unitPrice, 2) . '/tab)';
                    } else {
                        $label .= ' - ₹' . number_format((float)$med->price, 2);
                    }
                @endphp
                <option value="{{ $med->id }}" 
                    data-price="{{ $med->price }}" 
                    data-unit-price="{{ $unitPrice }}" 
                    data-items-per-strip="{{ $stripQty }}" 
                    data-unit="{{ $med->unit }}" 
                    data-stock="{{ $med->stock }}">{{ $label }}</option>
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
            $('.select2-timing').select2({
                theme: 'bootstrap-5',
                tags: true
            });

            let rowCount = 0;

            function calculateRevenue() {
                let subtotal = 0;
                $('.qty-input').each(function () {
                    let qty = parseFloat($(this).val()) || 0;
                    let tr = $(this).closest('tr');
                    let sel = tr.find('.select2-primary option:selected, .select2-med option:selected').filter('[value!=""]');
                    let unitPrice = parseFloat(sel.data('unit-price'));
                    let rawPrice = parseFloat(sel.data('price')) || 0;
                    let itemsPerStrip = parseInt(sel.data('items-per-strip')) || 1;

                    let rate = (!isNaN(unitPrice) && unitPrice > 0) ? unitPrice : (itemsPerStrip > 1 ? (rawPrice / itemsPerStrip) : rawPrice);
                    subtotal += (qty * rate);
                });
                $('#subtotal').val(subtotal.toFixed(2));

                let discountType = $('#discount_type').val();
                let discountValue = parseFloat($('#discount_value').val()) || 0;
                let finalAmount = subtotal;

                if (discountType === 'percentage') {
                    finalAmount = subtotal - (subtotal * (discountValue / 100));
                } else if (discountType === 'flat') {
                    finalAmount = subtotal - discountValue;
                }

                finalAmount = Math.max(0, finalAmount);
                $('#final_amount').val(finalAmount.toFixed(2));

                let received = $('#received_amount').val();
                if (received === '' || received === null) {
                    $('#due_amount').val('0.00');
                } else {
                    let due = finalAmount - parseFloat(received);
                    $('#due_amount').val(due.toFixed(2));
                }
            }

            $(document).on('input', '.qty-input', calculateRevenue);
            $(document).on('change', '.select2-primary, .select2-med', calculateRevenue);
            $('#discount_type, #discount_value, #received_amount').on('input change', calculateRevenue);

            $('#addMedicineBtn').click(function () {
                rowCount++;
                $('#emptyState').hide();
                $('#revenueSection').show();

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
                                    @php
                                        $stripQty = ($med->items_per_strip && (int)$med->items_per_strip > 1) ? (int)$med->items_per_strip : 1;
                                        $unitPrice = $stripQty > 1 ? round((float)$med->price / $stripQty, 2) : (float)$med->price;
                                        $label = $med->name . ' (Stock: ' . $med->stock . ')';
                                        if ($stripQty > 1) {
                                            $label .= ' - ₹' . number_format((float)$med->price, 2) . '/strip (₹' . number_format($unitPrice, 2) . '/tab)';
                                        } else {
                                            $label .= ' - ₹' . number_format((float)$med->price, 2);
                                        }
                                    @endphp
                                    <option value="{{ $med->id }}" 
                                        data-price="{{ $med->price }}" 
                                        data-unit-price="{{ $unitPrice }}" 
                                        data-items-per-strip="{{ $stripQty }}" 
                                        data-unit="{{ $med->unit }}" 
                                        data-stock="{{ $med->stock }}">{{ $label }}</option>
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

            // Problem & YouTube Link Logic
            $('#youtubeLinkSelect').select2({
                theme: 'bootstrap-5',
                tags: true,
                placeholder: "Select or type links...",
                allowClear: true
            });

            $('#problemSelect').change(function () {
                let selectedOpt = $(this).find('option:selected');
                let links = selectedOpt.data('links') || [];

                let html = '';
                if (typeof links === 'string') {
                    try { links = JSON.parse(links); } catch (e) { links = []; }
                }

                if (Array.isArray(links)) {
                    links.forEach(function (link) {
                        if (link) {
                            html += `<option value="${link}">${link}</option>`;
                        }
                    });
                }

                // Keep current custom selections
                let currentSelection = $('#youtubeLinkSelect').val() || [];
                currentSelection.forEach(function (val) {
                    if (html.indexOf(`value="${val}"`) === -1) {
                        html += `<option value="${val}" selected>${val}</option>`;
                    }
                });

                $('#youtubeLinkSelect').html(html);
                $('#youtubeLinkSelect').val(currentSelection).trigger('change');
            });

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

            // Handle Load Template
            $('#loadTemplateSelect').change(function() {
                let templateId = $(this).val();
                if(!templateId) return;

                $.ajax({
                    url: `/prescription-templates/${templateId}`,
                    type: "GET",
                    success: function(template) {
                        // Populate Problem
                        if(template.problem_id) {
                            $('#problemSelect').val(template.problem_id).trigger('change');
                        } else {
                            $('#problemSelect').val('').trigger('change');
                        }

                        // Populate YouTube Links
                        let links = template.youtube_links || [];
                        let html = '';
                        links.forEach(function (link) {
                            html += `<option value="${link}" selected>${link}</option>`;
                        });
                        $('#youtubeLinkSelect').html(html).trigger('change');

                        // Populate Notes & HOPI
                        $('textarea[name="hopi"]').val(template.hopi || '');
                        $('textarea[name="notes"]').val(template.notes || '');

                        // Clear existing medicines
                        $('#medicineList').empty();
                        
                        // Add medicines from template
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
                                                    @php
                                                        $stripQty = ($med->items_per_strip && (int)$med->items_per_strip > 1) ? (int)$med->items_per_strip : 1;
                                                        $unitPrice = $stripQty > 1 ? round((float)$med->price / $stripQty, 2) : (float)$med->price;
                                                        $label = $med->name . ' (Stock: ' . $med->stock . ')';
                                                        if ($stripQty > 1) {
                                                            $label .= ' - ₹' . number_format((float)$med->price, 2) . '/strip (₹' . number_format($unitPrice, 2) . '/tab)';
                                                        } else {
                                                            $label .= ' - ₹' . number_format((float)$med->price, 2);
                                                        }
                                                    @endphp
                                                    <option value="{{ $med->id }}" 
                                                        data-price="{{ $med->price }}" 
                                                        data-unit-price="{{ $unitPrice }}" 
                                                        data-items-per-strip="{{ $stripQty }}" 
                                                        data-unit="{{ $med->unit }}" 
                                                        data-stock="{{ $med->stock }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="alternate_medicines[${rowCount}][]" class="form-select select2-med select2-alt w-100" multiple="multiple" data-placeholder="Select...">
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}" ${item.alternate_medicine_id && item.alternate_medicine_id.split(',').includes(String('{{ $med->id }}')) ? 'selected' : ''}>{{ $med->name }}</option>
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
                                $(`#row-${rowCount} select[name="alternate_medicines[${rowCount}][]"]`).select2({ theme: 'bootstrap-5' });
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
                $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: "{{ route('prescriptions.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.success);
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Prescription');
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