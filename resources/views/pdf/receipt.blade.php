<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt - {{ $transaction->reference_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.4;
            padding: 40px;
        }
        .top-section {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .top-left {
            display: table-cell;
            width: 70%;
            vertical-align: top;
        }
        .top-right {
            display: table-cell;
            width: 30%;
            text-align: right;
            vertical-align: top;
        }
        .logo {
            width: 50px;
            height: 50px;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #000;
            margin-bottom: 12px;
        }
        .meta-info {
            font-size: 10px;
            color: #525252;
            line-height: 1.5;
        }
        .two-column {
            display: table;
            width: 100%;
            margin: 25px 0;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .column-title {
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 6px;
            color: #000;
        }
        .column-content {
            font-size: 10px;
            color: #525252;
            line-height: 1.5;
        }
        .amount-paid {
            font-size: 20px;
            font-weight: 700;
            margin: 25px 0;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #d4d4d4;
            font-size: 10px;
            font-weight: 600;
            color: #525252;
        }
        td {
            padding: 12px 0;
            border-bottom: 1px solid #e5e5e5;
            font-size: 10px;
            color: #1a1a1a;
        }
        .item-description {
            color: #000;
            font-size: 11px;
            font-weight: 500;
        }
        .item-details {
            color: #525252;
            font-size: 10px;
            margin-top: 2px;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
        }
        .totals-row {
            display: table;
            width: 100%;
            margin: 6px 0;
        }
        .totals-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            font-size: 10px;
            color: #525252;
        }
        .totals-value {
            display: table-cell;
            text-align: right;
            width: 100px;
            font-size: 10px;
            color: #1a1a1a;
            font-weight: 500;
        }
        .totals-row.total {
            font-weight: 600;
            margin-top: 5px;
            padding-top: 6px;
            border-top: 1px solid #d4d4d4;
        }
        .totals-row.amount-paid {
            font-weight: 700;
        }
        .payment-history {
            margin-top: 40px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #000;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            font-size: 9px;
            color: #737373;
            line-height: 1.5;
        }
        .footer-company {
            font-weight: 600;
            color: #525252;
        }
    </style>
</head>
<body>
    <div class="top-section">
        <div class="top-left">
            <h1>Receipt</h1>
            <div class="meta-info">
                Invoice number: {{ $transaction->reference_number }}<br>
                Receipt number: {{ $transaction->reference_number }}<br>
                Date paid: {{ $transaction->created_at->format('F j, Y') }}
            </div>
        </div>
        <div class="top-right">
            <div class="logo">
                <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo">
            </div>
        </div>
    </div>

    <div class="two-column">
        <div class="column">
            <div class="column-title">AbogadoMo</div>
            <div class="column-content">
                Philippines<br>
                support@abogadomo.com
            </div>
        </div>
        <div class="column">
            <div class="column-title">Bill to</div>
            <div class="column-content">
                {{ $transaction->user->name }}<br>
                @if($transaction->user->city && $transaction->user->province)
                {{ $transaction->user->city }}<br>
                {{ $transaction->user->province }}<br>
                @endif
                Philippines<br>
                {{ $transaction->user->email }}
            </div>
        </div>
    </div>

    <div class="amount-paid">
        ₱{{ number_format($transaction->amount, 2) }} paid on {{ $transaction->created_at->format('F j, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right" style="width: 60px;">Qty</th>
                <th class="text-right" style="width: 80px;">Unit price</th>
                <th class="text-right" style="width: 80px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="item-description">
                        @if($transaction->type === 'consultation_payment')
                            Legal Consultation
                        @else
                            Document Drafting Service
                        @endif
                    </div>
                    <div class="item-details">
                        @if($transaction->consultation)
                            {{ ucfirst($transaction->consultation->type) }} consultation with {{ $transaction->consultation->lawyer->name }}
                        @elseif($transaction->documentRequest)
                            {{ $transaction->documentRequest->documentTemplate->name ?? 'Document' }} by {{ $transaction->documentRequest->lawyer->name }}
                        @endif
                    </div>
                </td>
                <td class="text-right">1</td>
                <td class="text-right">₱{{ number_format($transaction->amount, 2) }}</td>
                <td class="text-right">₱{{ number_format($transaction->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row">
            <div class="totals-label">Subtotal</div>
            <div class="totals-value">₱{{ number_format($transaction->amount, 2) }}</div>
        </div>
        <div class="totals-row total">
            <div class="totals-label">Total</div>
            <div class="totals-value">₱{{ number_format($transaction->amount, 2) }}</div>
        </div>
        <div class="totals-row amount-paid">
            <div class="totals-label">Amount paid</div>
            <div class="totals-value">₱{{ number_format($transaction->amount, 2) }}</div>
        </div>
    </div>

    <div class="payment-history">
        <div class="section-title">Payment history</div>
        <table>
            <thead>
                <tr>
                    <th>Payment method</th>
                    <th>Date</th>
                    <th class="text-right">Amount paid</th>
                    <th class="text-right">Receipt number</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'Card')) }}</td>
                    <td>{{ $transaction->created_at->format('F j, Y') }}</td>
                    <td class="text-right">₱{{ number_format($transaction->amount, 2) }}</td>
                    <td class="text-right">{{ $transaction->reference_number }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        All charges and prices are in Philippine Peso<br>
        <span class="footer-company">AbogadoMo</span> - Legal Services Platform<br>
        Philippines<br>
        <br>
        This is an official receipt for your payment transaction.<br>
        Please keep this receipt for your records.
    </div>
</body>
</html>
