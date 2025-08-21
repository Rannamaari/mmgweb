<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #DC2626;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #DC2626;
        }

        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }

        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .invoice-left,
        .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-right {
            text-align: right;
        }

        .invoice-number {
            font-size: 18px;
            font-weight: 600;
            color: #DC2626;
            margin-bottom: 10px;
        }

        .customer-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #DC2626;
        }

        .vehicle-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f9ff;
            border-radius: 6px;
            border-left: 4px solid #0ea5e9;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #e5e7eb;
            padding: 12px 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            margin-left: 60%;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .total-row {
            font-weight: 600;
            font-size: 14px;
            border-top: 2px solid #DC2626 !important;
            background-color: #fef2f2;
        }

        .payments {
            margin-top: 25px;
        }

        .payment-info {
            margin-top: 25px;
            padding: 15px;
            background-color: #f0fdf4;
            border-radius: 8px;
            border-left: 4px solid #16a34a;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }

        .bank-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }

        .bank-details h4 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 13px;
        }

        .bank-details p {
            margin: 5px 0;
            font-size: 11px;
        }

        .highlight {
            color: #DC2626;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">MICRO MOTO GARAGE</div>
        <div class="company-details">
            H. Golden Meet aage, Janavaree Hingun, Malé<br>
            Email: moto@micronet.mv | Website: www.garage.micronet.mv | Phone: 9996210<br>
            Operating Hours: 8:00 AM - 10:00 PM | Friday: Closed
        </div>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
        <div class="invoice-left">
            <div class="invoice-number">INVOICE #{{ $invoice->number }}</div>
            <strong>Date:</strong> {{ $invoice->date->setTimezone('Indian/Maldives')->format('d/m/Y H:i') }}<br>
            <strong>Status:</strong> <span class="highlight">{{ strtoupper($invoice->status) }}</span>
        </div>
        <div class="invoice-right">
            @if($invoice->customer)
                <div class="customer-info">
                    <strong>Bill To:</strong><br>
                    <strong>{{ $invoice->customer->name }}</strong><br>
                    📞 {{ $invoice->customer->phone }}<br>
                    @if($invoice->customer->email)
                        📧 {{ $invoice->customer->email }}<br>
                    @endif
                    @if($invoice->customer->address)
                        📍 {{ $invoice->customer->address }}
                    @endif
                </div>
            @else
                <div class="customer-info">
                    <strong>CASH SALE</strong><br>
                    Walk-in Customer
                </div>
            @endif
        </div>
    </div>

    <!-- Vehicle Info -->
    @if($invoice->motorcycle)
        <div class="vehicle-info">
            <strong>Vehicle:</strong> {{ $invoice->motorcycle->make }} {{ $invoice->motorcycle->model }}
            @if($invoice->motorcycle->year)
                ({{ $invoice->motorcycle->year }})
            @endif
            @if($invoice->motorcycle->plate_no)
                - {{ $invoice->motorcycle->plate_no }}
            @endif
        </div>
    @endif

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Type</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Rate (ރ)</th>
                <th class="text-right">Amount (ރ)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td><strong>{{ $item->description }}</strong></td>
                    <td>{{ ucfirst($item->item_type) }}</td>
                    <td class="text-right">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right"><strong>{{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-{{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            @if($invoice->tax > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">{{ number_format($invoice->tax, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>{{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Bank Transfer Details -->
    <div class="bank-details">
        <h4>Bank Transfer Details</h4>
        <p><strong>Bank:</strong> Bank of Maldives (BML)</p>
        <p><strong>Account Name:</strong> Micronet</p>
        <p><strong>Account Number:</strong> <span class="highlight">7730000140010</span></p>
        <p><strong>Important:</strong> Please send payment slip to WhatsApp/Viber: <span
                class="highlight">9996210</span></p>
    </div>



    @if($invoice->notes)
        <div
            style="margin-top: 20px; padding: 15px; background-color: #f8fafc; border-radius: 6px; border-left: 4px solid #64748b;">
            <strong>Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank You for Choosing Micro Moto Garage!</strong></p>
        <p>Payment Methods: Cash / Bank Transfer</p>
        <p>For any queries, contact us at 9996210</p>
    </div>
</body>

</html>