<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
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

        .customer-info {
            margin-bottom: 20px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-left: 60%;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #333 !important;
        }

        .payments {
            margin-top: 20px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">🏍️ MICRO MOTO GARAGE</div>
        <div class="company-tagline">Professional Motorcycle Service & Parts</div>
        <div style="font-size: 10px;">
            123 Main Road, Your City | Phone: +91 98765 43210 | Email: info@micromotogarage.com
        </div>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
        <div class="invoice-left">
            <strong>INVOICE</strong><br>
            <strong>Number:</strong> {{ $invoice->number }}<br>
                            <strong>Date:</strong> {{ $invoice->date->setTimezone('Indian/Maldives')->format('d/m/Y H:i') }}<br>
            <strong>Status:</strong> {{ strtoupper($invoice->status) }}
        </div>
        <div class="invoice-right">
            @if($invoice->customer)
                <strong>Bill To:</strong><br>
                {{ $invoice->customer->name }}<br>
                {{ $invoice->customer->phone }}<br>
                @if($invoice->customer->gst_number)
                    <strong>GST:</strong> {{ $invoice->customer->gst_number }}<br>
                @endif
                @if($invoice->customer->address)
                    {{ $invoice->customer->address }}
                @endif
            @else
                <strong>CASH SALE</strong><br>
                Walk-in Customer
            @endif
        </div>
    </div>

    <!-- Vehicle Info -->
    @if($invoice->motorcycle)
        <div class="customer-info">
            <strong>Vehicle:</strong> {{ $invoice->motorcycle->make }} {{ $invoice->motorcycle->model }}
            ({{ $invoice->motorcycle->year ?? 'N/A' }}) - {{ $invoice->motorcycle->plate_no }}
        </div>
    @endif

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Type</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ ucfirst($item->item_type) }}</td>
                    <td class="text-right">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-right">ރ{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">ރ{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">ރ{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-ރ{{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            @if($invoice->tax > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">ރ{{ number_format($invoice->tax, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>ރ{{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Payments -->
    @if($invoice->payments->count() > 0)
        <div class="payments">
            <h3>Payments</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->received_at->setTimezone('Indian/Maldives')->format('d/m/Y H:i') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                            <td>{{ $payment->reference_no ?? '-' }}</td>
                            <td class="text-right">₹{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3"><strong>Total Paid:</strong></td>
                        <td class="text-right"><strong>ރ{{ number_format($invoice->payments->sum('amount'), 2) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @if($invoice->notes)
        <div style="margin-top: 20px;">
            <strong>Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank You for Your Business!</strong></p>
        <p>Payment Methods: Cash / Bank Transfer Only</p>
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>