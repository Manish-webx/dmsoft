<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Prescription #{{ $prescription->id }}</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        header {
            /* normal flow */
        }

        footer {
            margin-top: 30px;
            /* space between content and footer */
        }

        .header-bg {
            background-color: #9e556373;
            padding: 15px 30px 15px 30px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 5px;
        }

        .logo-section h1 {
            margin: 0;
            font-size: 38px;
            font-weight: bold;
            color: #2b2b2b;
            letter-spacing: 4px;
        }

        .tagline {
            margin: 0;
            font-size: 13px;
            font-style: italic;
            color: #555;
            letter-spacing: 1px;
        }

        .services {
            margin: 5px 0 0 0;
            font-size: 10px;
            font-weight: bold;
            color: #333;
            letter-spacing: 0.5px;
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
            font-size: 12px;
            color: #1B2A4A;
            margin-bottom: 8px;
            text-align: left;
        }

        .branch-line {
            margin-bottom: 3px;
            text-align: left;
        }

        .qr-placeholder {
            width: 50px;
            height: 50px;
            background-color: #eee;
            border: 1px solid #ccc;
            text-align: center;
            line-height: 50px;
            font-size: 9px;
            color: #888;
        }

        .footer-branches-table {
            width: 100%;
            font-size: 9px;
            color: #333;
        }

        .content {
            padding: 15px 30px;
        }

        .info-table,
        .clinical-table,
        .med-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .info-table td,
        .clinical-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
            background-color: #fdfdfd;
        }

        .label {
            font-weight: bold;
            color: #2c3e50;
        }

        .med-table th,
        .med-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
        }

        .med-table th {
            background-color: #f4f6f9;
            font-weight: bold;
            color: #2c3e50;
            text-align: left;
        }

        .instruction-row td {
            border-top: none;
            padding-top: 2px;
            padding-bottom: 8px;
            background-color: #fafafa;
        }

        .instruction-label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
        }

        .rx-symbol {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .footer-sig-table {
            width: 100%;
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        .footer-sig-table td {
            vertical-align: bottom;
        }

        .footer-sig-container {
            padding: 15px 30px 15px 30px;
        }

        .footer-bg {
            background-color: #9e556373;
            padding: 15px 30px;
            border-top: 2px solid #9e556373;
            font-size: 12px;
            color: #555;
        }

        .revenue-table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        .revenue-table td {
            padding: 5px;
        }
    </style>
</head>

<body>

    <header>
        <div class="header-bg">
            <!-- <div class="logo-section">
                <img src="{{ public_path('assets/image/logo1.png') }}" style="width:150px">
            </div> -->

            <table class="doctor-info-table">
                <tr>
                    <td width="50%" style="vertical-align: top; text-align: left;">
                        <div class="logo-section">
                            <img src="{{ public_path('assets/image/logo1.png') }}" style="width:150px">
                        </div>
                    </td>
                    <td width="50%" style="vertical-align: top; text-align: right;">
                        <div class="doc-name">Dr. Pooja Varshney</div>
                        <div class="doc-title">MBBS, MD (SKIN & VD)</div>
                        <div class="doc-title">Consultant Dermatologist</div>
                        <div class="doc-details">Reg. No. DMC/R/72362</div>
                        <div class="doc-details">Member of Indian Association of Dermatologists,<br>Venereologists and
                            Leprosy(IADVL)</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="speciality-bar">
            DERMATOLOGIST | COSMETOLOGIST | DERMATOSURGEON | HAIR TRANSPLANT SURGEON
        </div>
    </header>



    <main class="content">
        <table class="info-table">
            <tr>
                <td width="50%">
                    <div style="margin-bottom: 5px;"><span class="label">Patient Name:</span>
                        {{ $prescription->patient->name }}</div>
                    <div style="margin-bottom: 5px;"><span class="label">Age/DOB:</span>
                        {{ $prescription->patient->dob ?? 'N/A' }}</div>
                    <div><span class="label">Gender:</span> {{ $prescription->patient->gender ?? 'N/A' }}</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div style="margin-bottom: 5px;"><span class="label">Prescription Date:</span>
                        {{ $prescription->date->format('d M Y') }}</div>
                    <div style="margin-bottom: 5px;"><span class="label">Doctor:</span>
                        {{ $prescription->doctor->name }}
                    </div>
                    <div><span class="label">Patient ID:</span> {{ $prescription->patient->patient_code }}</div>
                </td>
            </tr>
        </table>

        <table class="clinical-table">
            <tr>
                <td width="50%">
                    @if($prescription->hopi)
                        <div style="margin-bottom: 10px;">
                            <span class="label">HOPI:</span><br>
                            {{ $prescription->hopi }}
                        </div>
                    @endif
                    <div style="margin-bottom: 10px;">
                        <span class="label">Notes:</span><br>
                        {{ $prescription->notes ?: 'N/A' }}
                    </div>
                    <div style="border-top: 1px solid #eee; padding-top: 10px;">
                        <span class="label">Next Follow-up:</span>
                        {{ $prescription->next_followup_date ? $prescription->next_followup_date->format('d M Y') : 'N/A' }}
                    </div>
                </td>
                <td width="50%">
                    <div style="margin-bottom: 10px;">
                        <span class="label">Diagnosis:</span><br>
                        {{ $prescription->problem ? $prescription->problem->name : 'N/A' }}
                    </div>

                    @if($prescription->youtube_links && count($prescription->youtube_links) > 0)
                        <div style="border-top: 1px solid #eee; padding-top: 10px;">
                            <span class="label">Youtube Links:</span><br>
                            @foreach($prescription->youtube_links as $link)
                                <a href="{{ $link }}" style="color: #4a6fa5;">{{ $link }}</a><br>
                            @endforeach
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <div class="rx-symbol">Rx</div>

        <table class="med-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Medicine Name</th>
                    <th width="15%">Timing</th>
                    <th width="20%">Duration</th>
                    <th width="25%">Alternate Medicine</th>
                    <th width="10%">Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescription->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->medicine->name }}</strong></td>
                        <td>{{ $item->timing ?: '-' }}</td>
                        <td>{{ $item->duration ?: '-' }}</td>
                        <td>{{ $item->alternateMedicine ? $item->alternateMedicine->name : '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                    @if($item->medicine_note)
                        <tr class="instruction-row">
                            <td class="instruction-label">Note</td>
                            <td colspan="5"><em>{{ $item->medicine_note }}</em></td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No medicines prescribed.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($prescription->revenue)
            <div style="margin-top: 20px; border: 1px solid #ddd; padding: 15px; background-color: #fdfdfd;">
                <span class="label" style="font-size: 14px;">Revenue Summary:</span>
                <table class="revenue-table">
                    <tr>
                        <td width="50%">Subtotal:</td>
                        <td width="50%">₹{{ $prescription->revenue->subtotal }}</td>
                    </tr>
                    <tr>
                        <td>Discount:</td>
                        <td>₹{{ $prescription->revenue->discount_value }}
                            ({{ ucfirst($prescription->revenue->discount_type) }})</td>
                    </tr>
                    <tr>
                        <td><strong>Final Amount:</strong></td>
                        <td><strong>₹{{ $prescription->revenue->final_amount }}</strong>
                            ({{ $prescription->revenue->payment_mode }})</td>
                    </tr>
                    @if($prescription->revenue->due_amount > 0)
                        <tr>
                            <td>Received:</td>
                            <td>₹{{ $prescription->revenue->received_amount }}</td>
                        </tr>
                        <tr>
                            <td><strong style="color:red;">Due Amount:</strong></td>
                            <td><strong style="color:red;">₹{{ $prescription->revenue->due_amount }}</strong></td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif
        </div>

    </main>

    <footer>
        <div class="footer-sig-container">
            <table class="footer-sig-table">
                <tr>
                    <td width="33%" style="text-align: left; vertical-align: bottom;">
                        <div class="qr-placeholder" style="margin-bottom: 5px;">QR Code</div>
                        <div style="font-size: 10px;">NOT FOR MEDICO-LEGAL PURPOSE</div>
                    </td>
                    <td width="33%" style="text-align: center; vertical-align: bottom; padding-bottom: 2px;">
                        <div>VALID FOR 7 DAYS</div>
                    </td>
                    <td width="33%" style="text-align: right; vertical-align: bottom;">
                        <div style="margin-bottom: 20px; border-bottom: 1px solid #333; width: 120px; float: right;">
                        </div>
                        <div style="clear: both;"></div>
                        <div>{{ $prescription->doctor->name }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-bg">
            <table class="footer-branches-table">
                <tr>
                    <td width="70%">
                        <div class="branch-title">Our Branches .:</div>
                        <div class="branch-line"><span style="font-family: DejaVu Sans;">&#x27A4;</span> 694, Sector 31,
                            Gurugram, Haryana 122001 <span style="font-family: DejaVu Sans;">&#x260E;</span>
                            <a href="tel:9560015155">+91 95600 15155</a>
                        </div>
                        <div class="branch-line"><span style="font-family: DejaVu Sans;">&#x27A4;</span> West Patel
                            Nagar, New Delhi - 110008 <span style="font-family: DejaVu Sans;">&#x260E;</span><a
                                href="tel:8700530623">
                                +91 87005 30623</a>
                        </div>
                    </td>
                    <td width="30%" style="vertical-align: top; text-align: right; padding-top: 20px;">
                        <div class="branch-line" style="text-align: right;">E-mail : dermatales@gmail.com</div>
                        <div class="branch-line" style="text-align: right;">Website : www.dermatales.com</div>
                        <div class="branch-line" style="text-align: right;">
                            <a href="https://www.facebook.com/profile?id=61572154471704"><img
                                    src="{{ public_path('assets/image/fb.png') }}"
                                    style="width:10px; margin-right: 3px;"></a>
                            <a href="https://www.instagram.com/dermatales/"><img
                                    src="{{ public_path('assets/image/ig.png') }}"
                                    style="width:10px; margin-right: 3px;"></a>
                            @dermatales
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </footer>

</body>

</html>