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

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .info-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
            background-color: #fdfdfd;
        }

        .label {
            font-weight: bold;
            color: #2c3e50;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
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
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        .revenue-table th, .revenue-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .revenue-table th {
            background-color: #f4f6f9;
            text-align: left;
        }
        
        .summary-box {
            width: 50%;
            float: right;
            margin-top: 20px;
            border-collapse: collapse;
        }
        
        .summary-box td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
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
                            <img src="{{ public_path('assets/image/logo1.png') }}" style="width:150px">
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
                <td width="50%">
                    <div style="margin-bottom: 5px;"><span class="label">Patient Name:</span>
                        {{ $prescription->patient?->name ?? 'Unknown Patient' }}</div>
                    <div style="margin-bottom: 5px;"><span class="label">Patient ID:</span>
                        {{ $prescription->patient?->patient_code ?? 'N/A' }}</div>
                    <div><span class="label">Gender/Age:</span> {{ $prescription->patient?->gender ?? 'N/A' }} / {{ $prescription->patient?->dob ?? 'N/A' }}</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div style="margin-bottom: 5px;"><span class="label">Bill Date:</span>
                        {{ $prescription->date?->format('d M Y') ?? 'N/A' }}</div>
                    <div style="margin-bottom: 5px;"><span class="label">Prescription #:</span>
                        {{ $prescription->id }}
                    </div>
                    <div><span class="label">Doctor:</span> {{ $prescription->doctor?->name ?? 'Unknown Doctor' }}</div>
                </td>
            </tr>
        </table>

        @if($prescription->revenue)
            <table class="revenue-table">
                <thead>
                    <tr>
                        <th width="70%">Description</th>
                        <th width="30%" style="text-align: right;">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Consultation & Medicines (Subtotal)</td>
                        <td style="text-align: right;">{{ number_format($prescription->revenue->subtotal, 2) }}</td>
                    </tr>
                    @if($prescription->revenue->discount_value > 0)
                    <tr>
                        <td>Discount ({{ ucfirst($prescription->revenue->discount_type) }})</td>
                        <td style="text-align: right; color: green;">-{{ number_format($prescription->revenue->discount_value, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="clearfix">
                <table class="summary-box">
                    <tr>
                        <td width="60%"><strong>Final Amount:</strong></td>
                        <td width="40%" style="text-align: right;"><strong>₹{{ number_format($prescription->revenue->final_amount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Received Amount ({{ $prescription->revenue->payment_mode }}):</td>
                        <td style="text-align: right;">₹{{ number_format($prescription->revenue->received_amount, 2) }}</td>
                    </tr>
                    @if($prescription->revenue->due_amount > 0)
                    <tr>
                        <td><strong style="color:red;">Due Amount:</strong></td>
                        <td style="text-align: right;"><strong style="color:red;">₹{{ number_format($prescription->revenue->due_amount, 2) }}</strong></td>
                    </tr>
                    @endif
                </table>
            </div>
        @else
            <div style="text-align: center; margin-top: 50px; color: #777;">
                <h3>No Billing Information Available</h3>
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
                    <td width="33%" style="text-align: center; vertical-align: bottom; padding-bottom: 2px;">
                        <div>Thank you!</div>
                    </td>
                    <td width="33%" style="text-align: right; vertical-align: bottom;">
                        <div style="margin-bottom: 20px; border-bottom: 1px solid #333; width: 150px; float: right;">
                        </div>
                        <div style="clear: both;"></div>
                        <div>Authorized Signatory</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-bg">
            <table class="footer-branches-table">
                <tr>
                    <td width="70%">
                        <div class="branch-title">Our Branches .:</div>
                        <div class="branch-line"><span style="font-family: DejaVu Sans;">&#x27A4;</span> 694, Sector 31, Gurugram, Haryana 122001 <span style="font-family: DejaVu Sans;">&#x260E;</span>
                            <a href="tel:9560015155">+91 95600 15155</a>
                        </div>
                        <div class="branch-line"><span style="font-family: DejaVu Sans;">&#x27A4;</span> West Patel Nagar, New Delhi - 110008 <span style="font-family: DejaVu Sans;">&#x260E;</span><a href="tel:8700530623">
                                +91 87005 30623</a>
                        </div>
                    </td>
                    <td width="30%" style="vertical-align: top; text-align: right; padding-top: 20px;">
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
