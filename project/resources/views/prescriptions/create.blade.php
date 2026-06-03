@extends('layouts.dashboard')
@section('title', 'Write Prescription')
@section('content')

    <form id="prescriptionForm">
        <div class="row">
            <!-- Patient Details -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <i class="fas fa-user-injured text-primary"></i> Patient Information
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Select Patient *</label>
                            <select name="patient_id" class="form-select select2 w-100" required>
                                <option value="">Search Patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->patient_code }} - {{ $patient->name }}
                                        ({{ $patient->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Next Follow-up Date</label>
                            <input type="date" name="next_followup_date" class="form-control">
                            <small class="text-muted">Will automatically create a Follow-up appointment.</small>
                        </div>
                        <div class="mb-3">
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
                        <div class="mb-3">
                            <label>YouTube Links</label>
                            <select name="youtube_links[]" id="youtubeLinkSelect" class="form-select select2 w-100"
                                multiple="multiple">
                                <!-- Options will be populated via JS -->
                            </select>
                            <small class="text-muted">Select from problem or type a custom link and press enter.</small>
                        </div>
                        <div class="mb-3">
                            <label>History of Present Illness (HOPI)</label>
                            <textarea name="hopi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicine Selection -->
            <div class="col-md-9">
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
                        <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">
                            <i class="fas fa-save"></i> Save Prescription
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
                <option value="{{ $med->id }}" data-price="{{ $med->price }}" data-stock="{{ $med->stock }}">{{ $med->name }}
                    (Stock: {{ $med->stock }}) - ₹{{ $med->price }}</option>
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
                    let price = parseFloat(tr.find('.select2-med option:selected').data('price')) || 0;
                    subtotal += (qty * price);
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

                $('#subtotal').val(subtotal.toFixed(2));
                $('#final_amount').val(finalAmount.toFixed(2));

                let received = $('#received_amount').val();
                if (received === '' || received === null) {
                    $('#due_amount').val('0.00');
                } else {
                    let due = finalAmount - parseFloat(received);
                    $('#due_amount').val(due.toFixed(2));
                }
            }

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
                            <select name="medicines[]" class="form-select select2-med select2-primary w-100" required>
                                <option value="">Search...</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}" data-price="{{ $med->price }}" data-stock="{{ $med->stock }}">{{ $med->name }} (Stock: {{ $med->stock }}) - ₹{{ $med->price }}</option>
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

                $(`#row-${rowCount} .select2-med`).select2({
                    theme: 'bootstrap-5'
                });
                
                $(`#row-${rowCount} .select2-timing`).select2({
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