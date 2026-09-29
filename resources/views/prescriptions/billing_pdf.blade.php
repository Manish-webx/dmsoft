<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bill #{{ $prescription->id }}</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        header {
            /* normal flow */
        }

        footer {
            margin-top: 25px;
        }

        .header-bg {
            background-color: #9e556373;
            padding: 15px 30px 15px 30px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 5px;
        }

        .doctor-info-table {
            width: 100%;
            margin-top: 10px;
        }

        .doc-name {
            color: #1B2A4A;
            font-size: 16px;
            font-weight: bold;
        }

        .doc-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-top: 2px;
        }

        .doc-details {
            font-size: 9px;
            color: #444;
            margin-top: 1px;
        }

        .speciality-bar {
            background-color: #4b4b4b;
            color: #ffffff;
            text-align: center;
            padding: 5px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .branch-title {
            font-weight: bold;
            font-size: 11px;
            color: #1B2A4A;
            margin-bottom: 6px;
            text-align: left;
        }

        .branch-line {
            margin-bottom: 3px;
            text-align: left;
            font-size: 9px;
        }

        .footer-branches-table {
            width: 100%;
            font-size: 9px;
            color: #333;
        }

        .content {
            padding: 15px 30px;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #1B2A4A;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1px solid #ddd;
        }

        .info-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            vertical-align: top;
            background-color: #fcfcfc;
            font-size: 11px;
        }

        .label {
            font-weight: bold;
            color: #2c3e50;
        }

        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .billing-table th,
        .billing-table td {
            padding: 7px 8px;
            border: 1px solid #ddd;
        }

        .billing-table th {
            background-color: #f4f6f9;
            color: #1B2A4A;
            font-weight: bold;
            text-align: left;
            font-size: 11px;
        }

        .item-name {
            font-weight: bold;
            color: #1B2A4A;
            font-size: 11px;
        }

        .item-brand {
            color: #666;
            font-size: 10px;
            font-weight: normal;
        }

        .item-details {
            font-size: 9.5px;
            color: #555;
            margin-top: 2px;
            line-height: 1.3;
        }

        .item-note {
            font-size: 9.5px;
            color: #777;
            font-style: italic;
            margin-top: 1px;
        }

        .summary-container {
            width: 100%;
            margin-top: 10px;
        }

        .summary-box {
            width: 48%;
            float: right;
            border-collapse: collapse;
            font-size: 11px;
            border: 1px solid #ddd;
        }

        .summary-box td {
            padding: 6px 10px;
            border: 1px solid #ddd;
        }

        .summary-box tr.highlight {
            background-color: #f7fafc;
        }

        .notes-box {
            width: 48%;
            float: left;
            padding: 8px 10px;
            background-color: #fdfdfd;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            color: #555;
            box-sizing: border-box;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .footer-sig-table {
            width: 100%;
            font-size: 11px;
            font-weight: bold;
            color: #333;
        }

        .footer-sig-table td {
            vertical-align: bottom;
        }

        .footer-sig-container {
            padding: 10px 30px 10px 30px;
        }

        .footer-bg {
            background-color: #9e556373;
            padding: 12px 30px;
            border-top: 2px solid #9e556373;
            font-size: 11px;
            color: #555;
        }

        .badge-paid {
            color: #276749;
            font-weight: bold;
        }

        .badge-due {
            color: #c53030;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header>
        <div class="header-bg">
            <table class="doctor-info-table">
                <tr>
                    <td width="33%" style="vertical-align: top; text-align: left;">
                        <div class="doc-name">Dr. Pooja Varshney</div>
                        <div class="doc-title">MBBS, MD (SKIN & VD)</div>
                        <div class="doc-title">Consultant Dermatologist</div>
                        <div class="doc-details">Reg. No. DMC/R/72362</div>
                        <div class="doc-details">Member of Indian Association of Dermatologists,<br>Venereologists and Leprosy(IADVL)</div>
                    </td>
                    <td width="34%" style="vertical-align: top; text-align: center;">
                        <div class="logo-section">
                            <img src="{{ public_path('assets/image/logo1.png') }}" style="width:140px">
                        </div>
                    </td>
                    <td width="33%" style="vertical-align: top; text-align: right;">
                        <!-- Blank right side -->
                    </td>
                </tr>
            </table>
        </div>

        <div class="speciality-bar">
            DERMATOLOGIST | COSMETOLOGIST | DERMATOSURGEON | HAIR TRANSPLANT SURGEON
        </div>
    </header>

    <main class="content">
        <div class="invoice-title">Patient Bill / Receipt</div>

        <table class="info-table">
            <tr>
                <td width="52%">
                    <div style="margin-bottom: 4px;"><span class="label">Patient Name:</span>
                        {{ $prescription->patient?->name ?? 'Unknown Patient' }}</div>
                    <div style="margin-bottom: 4px;"><span class="label">Patient ID:</span>
                        {{ $prescription->patient?->patient_code ?? 'N/A' }}</div>
                    <div style="margin-bottom: 4px;"><span class="label">Gender / Age:</span>
                        {{ $prescription->patient?->gender ?? 'N/A' }} / {{ $prescription->patient?->dob ?? 'N/A' }}</div>
                    @if($prescription->patient?->phone && $prescription->patient?->phone !== 'N/A')
                    <div><span class="label">Phone:</span> {{ $prescription->patient->phone }}</div>
                    @endif
                </td>
                <td width="48%" style="text-align: right;">
                    <div style="margin-bottom: 4px;"><span class="label">Bill / Receipt #:</span>
                        <strong>#{{ $prescription->id }}</strong>
                    </div>
                    <div style="margin-bottom: 4px;"><span class="label">Bill Date:</span>
                        {{ $prescription->date?->format('d M Y') ?? now()->format('d M Y') }}</div>
                    <div style="margin-bottom: 4px;"><span class="label">Doctor:</span>
                        {{ $prescription->doctor?->name ?? 'Unknown Doctor' }}</div>
                    @if($prescription->revenue)
                    <div>
                        <span class="label">Payment Status:</span>
                        <span class="{{ ($prescription->revenue->due_amount > 0) ? 'badge-due' : 'badge-paid' }}">
                            {{ $prescription->revenue->due_amount > 0 ? 'Partial Due' : 'Paid' }}
                        </span>
                    </div>
                    @endif
                </td>
            </tr>
        </table>

        @php
            $itemsSum = 0;
            if($prescription->items && count($prescription->items) > 0) {
                foreach($prescription->items as $item) {
                    $itemsSum += ($item->medicine?->price ?? 0) * ($item->quantity ?? 1);
                }
            }
            $revenueSubtotal = $prescription->revenue?->subtotal ?? $itemsSum;
            $extraCharges = ($revenueSubtotal > $itemsSum && $itemsSum > 0) ? ($revenueSubtotal - $itemsSum) : 0;
        @endphp

        <table class="billing-table">
            <thead>
                <tr>
                    <th width="5%" style="text-align: center;">#</th>
                    <th width="48%">Medicine / Item Description</th>
                    <th width="12%" style="text-align: center;">Qty</th>
                    <th width="17%" style="text-align: right;">Rate (₹)</th>
                    <th width="18%" style="text-align: right;">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescription->items as $item)
                    @php
                        $rate = $item->medicine?->price ?? 0;
                        $qty = $item->quantity ?? 1;
                        $lineTotal = $rate * $qty;

                        $metaParts = [];
                        if ($item->time) $metaParts[] = $item->time;
                        if ($item->when) $metaParts[] = $item->when;
                        if ($item->frequency) $metaParts[] = $item->frequency;
                        if ($item->duration) $metaParts[] = $item->duration . ' ' . ($item->duration_type ?? 'Days');
                    @endphp
                    <tr>
                        <td style="text-align: center; vertical-align: top;">{{ $loop->iteration }}</td>
                        <td style="vertical-align: top;">
                            <span class="item-name">{{ $item->medicine?->name ?? 'Medicine #' . $item->medicine_id }}</span>
                            @if($item->medicine?->brand)
                                <span class="item-brand">({{ $item->medicine->brand }})</span>
                            @endif

                            @if(count($metaParts) > 0)
                                <div class="item-details">
                                    <span style="color: #4b4b4b; font-weight: 500;">Dosage:</span> {{ implode(' | ', $metaParts) }}
                                </div>
                            @endif

                            @if($item->medicine_note)
                                <div class="item-note">
                                    <span>Note: {{ $item->medicine_note }}</span>
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center; vertical-align: top;">
                            {{ $qty }}
                            @if($item->medicine?->unit)
                                <span style="font-size: 9px; color: #666;">{{ $item->medicine->unit }}</span>
                            @endif
                        </td>
                        <td style="text-align: right; vertical-align: top;">{{ number_format($rate, 2) }}</td>
                        <td style="text-align: right; vertical-align: top; font-weight: bold;">{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @empty
                    @if($revenueSubtotal > 0)
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td>
                                <span class="item-name">Consultation & Clinical Services</span>
                            </td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: right;">{{ number_format($revenueSubtotal, 2) }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($revenueSubtotal, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888; padding: 15px;">No bill items found.</td>
                        </tr>
                    @endif
                @endforelse

                @if($extraCharges > 0)
                    <tr>
                        <td style="text-align: center; vertical-align: top;">{{ count($prescription->items) + 1 }}</td>
                        <td style="vertical-align: top;">
                            <span class="item-name">Consultation / Additional Services</span>
                        </td>
                        <td style="text-align: center; vertical-align: top;">1</td>
                        <td style="text-align: right; vertical-align: top;">{{ number_format($extraCharges, 2) }}</td>
                        <td style="text-align: right; vertical-align: top; font-weight: bold;">{{ number_format($extraCharges, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if($prescription->revenue)
            @php
                $rev = $prescription->revenue;
                $discAmt = 0;
                if ($rev->discount_value > 0) {
                    if ($rev->discount_type == 'percentage') {
                        $discAmt = ($rev->subtotal * $rev->discount_value) / 100;
                    } else {
                        $discAmt = $rev->discount_value;
                    }
                }
            @endphp

            <div class="summary-container clearfix">
                <div class="notes-box">
                    <strong style="color: #1B2A4A; font-size: 10.5px;">Terms & Conditions:</strong>
                    <ul style="margin: 4px 0 0 15px; padding: 0; line-height: 1.4;">
                        <li>Medicines once sold are subject to clinic return/exchange policies.</li>
                        <li>Please take all medications strictly as advised by the doctor.</li>
                        <li>Keep this receipt for follow-up and future medical reference.</li>
                    </ul>
                </div>

                <table class="summary-box">
                    <tr>
                        <td width="55%"><strong>Subtotal:</strong></td>
                        <td width="45%" style="text-align: right;">₹{{ number_format($rev->subtotal, 2) }}</td>
                    </tr>
                    @if($rev->discount_value > 0)
                    <tr>
                        <td>
                            <strong>Discount</strong> 
                            @if($rev->discount_type == 'percentage')
                                <span style="font-size: 10px; color: #666;">({{ $rev->discount_value }}%)</span>
                            @else
                                <span style="font-size: 10px; color: #666;">(Flat)</span>
                            @endif
                            :
                        </td>
                        <td style="text-align: right; color: green; font-weight: bold;">-₹{{ number_format($discAmt, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="highlight">
                        <td><strong style="font-size: 11.5px; color: #1B2A4A;">Final Amount:</strong></td>
                        <td style="text-align: right;"><strong style="font-size: 11.5px; color: #1B2A4A;">₹{{ number_format($rev->final_amount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Received Amount ({{ ucfirst($rev->payment_mode ?? 'Cash') }}):</td>
                        <td style="text-align: right;">₹{{ number_format($rev->received_amount, 2) }}</td>
                    </tr>
                    @if($rev->due_amount > 0)
                    <tr style="background-color: #fff5f5;">
                        <td><strong style="color: #c53030;">Due Amount:</strong></td>
                        <td style="text-align: right;"><strong style="color: #c53030;">₹{{ number_format($rev->due_amount, 2) }}</strong></td>
                    </tr>
                    @else
                    <tr>
                        <td><strong>Due Amount:</strong></td>
                        <td style="text-align: right; color: #276749;"><strong>₹0.00 (Nil)</strong></td>
                    </tr>
                    @endif
                </table>
            </div>
        @endif

    </main>

    <footer>
        <div class="footer-sig-container">
            <table class="footer-sig-table">
                <tr>
                    <td width="33%" style="text-align: left; vertical-align: bottom;">
                        <!-- Optional QR or generic text -->
                    </td>
                    <td width="34%" style="text-align: center; vertical-align: bottom; padding-bottom: 2px;">
                        <div style="font-size: 11px; color: #555;">Thank You for Visiting!</div>
                    </td>
                    <td width="33%" style="text-align: right; vertical-align: bottom;">
                        <div style="margin-bottom: 15px; border-bottom: 1px solid #333; width: 140px; float: right;">
                        </div>
                        <div style="clear: both;"></div>
                        <div style="font-size: 10px;">Authorized Signatory</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-bg">
            <table class="footer-branches-table">
                <tr>
                    <td width="68%">
                        <div class="branch-title">Our Branches .:</div>
                        <div class="branch-line"><span style="font-family: DejaVu Sans;">&#x27A4;</span> 694, Sector 31, Gurugram, Haryana 122001 <span style="font-family: DejaVu Sans;">&#x260E;</span>
                            <a href="tel:9560015155" style="color: inherit; text-decoration: none;">+91 95600 15155</a>
                        </div>
                        <div class="branch-line"><span style="font-family: DejaVu Sans;">&#x27A4;</span> West Patel Nagar, New Delhi - 110008 <span style="font-family: DejaVu Sans;">&#x260E;</span><a href="tel:8700530623" style="color: inherit; text-decoration: none;">
                                +91 87005 30623</a>
                        </div>
                    </td>
                    <td width="32%" style="vertical-align: top; text-align: right; padding-top: 10px;">
                        <div class="branch-line" style="text-align: right;">E-mail : dermatales@gmail.com</div>
                        <div class="branch-line" style="text-align: right;">Website : www.dermatales.com</div>
                        <div class="branch-line" style="text-align: right;">
                            <a href="https://www.facebook.com/profile?id=61572154471704"><img src="{{ public_path('assets/image/fb.png') }}" style="width:10px; margin-right: 3px;"></a>
                            <a href="https://www.instagram.com/dermatales/"><img src="{{ public_path('assets/image/ig.png') }}" style="width:10px; margin-right: 3px;"></a>
                            @dermatales
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </footer>

</body>

</html>
