<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف حساب {{ $account->name }}</title>
    @php
$tajawalRegular = 'file:///' . str_replace('\\', '/', public_path('assets/fonts/Tajawal-Regular.ttf'));
$tajawalBold = 'file:///' . str_replace('\\', '/', public_path('assets/fonts/Tajawal-Bold.ttf'));
$logoPath = 'file:///' . str_replace('\\', '/', public_path('assets/img/logo3.png'));
$tablerIcons = 'file:///' . str_replace('\\', '/', resource_path('assets/vendor/fonts/tabler/tabler-icons.ttf'));
    @endphp
    <style>
        @font-face {
            font-family: 'Tajawal';
            font-style: normal;
            font-weight: 400;
            src: url('{{ $tajawalRegular }}') format('truetype');
        }
        @font-face {
            font-family: 'Tajawal';
            font-style: normal;
            font-weight: 700;
            src: url('{{ $tajawalBold }}') format('truetype');
        }
        @font-face {
            font-family: 'TablerIcons';
            font-style: normal;
            font-weight: normal;
            src: url('{{ $tablerIcons }}') format('truetype');
        }
        @page {
            margin: 20mm 12mm 20mm 12mm;
        }
        body {
            font-family: tajawal, 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #111;
        }
        .ar {
            direction: rtl;
            unicode-bidi: embed;
        }
        .top {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .top td {
            vertical-align: top;
            width: 50%;
            padding: 0 0 8px 0;
        }
        .top .right {
            width: 50%;
            text-align: right;
            line-height: 1.5;
        }
        .ti-icon {
            font-family: 'TablerIcons';
            font-size: 12px;
            display: inline-block;
            width: 16px;
            text-align: center;
            margin-left: 4px;
            vertical-align: middle;
        }
        .ti {
            font-family: 'TablerIcons';
            font-style: normal;
            font-weight: normal;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .ti:before {
            display: inline-block;
        }
        .ti-md {
            font-size: 12px;
        }
        .ti-eye:before {
            content: "\ea9a";
        }
        .top .left {
            width: 50%;
            text-align: left;
            line-height: 1.5;
        }
        .top .left img {
            display: block;
            height: auto;
            margin: 0;
        }
        .statement-title {
            text-align: center;
            background: #0A1F32;
            color: #fff;
            font-weight: bold;
            padding: 6px 8px;
            margin: 0 0 0;
        }
        table.statement {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        table.statement th,
        table.statement td {
            border: 1px solid #9099a1;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
        }
        table.statement th {
            background: #e7f2fb;
            font-weight: bold;
        }
        table.statement td.details {
            text-align: center;
        }
        .credit {
            color: #188d39;
            font-weight: bold;
        }
        .debit {
            color: #cf2e2e;
            font-weight: bold;
        }
        .summary-label {
            color: #111;
            font-weight: bold;
            text-align: right;
        }
        .summary-value {
            font-weight: bold;
        }
        .footer {
            margin-top: 10px;
            width: 100%;
            font-size: 11px;
            color: #555;
            text-align: center;
            direction: rtl
        }
    </style>
</head>
<body>
    <table class="top">
                <tr>
                    <td class="right">
                        <div>اويا للصرافة والمعاملات المالية</div>
                        <div>طرابلس - الظهرة</div>
                        <div>0923332733</div>
                    </td>
                    <td class="left">
                        <img src="{{ $logoPath }}" alt="OYA Logo" width="100" style="width:100px; height:auto;">
                    </td>
                </tr>
            </table>

    <div class="statement-title ar">كشف حساب {{ $account->name }}</div>

    <table class="statement">
        <thead>
            <tr>
                <th class="ar">التاريخ</th>
                <th class="ar">التفاصيل</th>
                <th class="ar">الرصيد</th>
                <th class="ar">له</th>
                <th class="ar">عليه</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                    <td class="details">{{ $transaction->description }}</td>
                    <td>{{ number_format((float) $transaction->balance_after, 2) }}</td>
                    <td class="credit">
                        {{ $transaction->type === 'credit' ? number_format((float) $transaction->amount, 2) : '' }}
                    </td>
                    <td class="debit">
                        {{ $transaction->type === 'debit' ? number_format((float) $transaction->amount, 2) : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="ar">لا توجد معاملات لهذا الحساب.</td>
                </tr>
            @endforelse
            <tr>
                <td class="summary-label ar" colspan="3">إجمالي العمليات</td>
                <td class="summary-value credit" colspan="1"> له {{ number_format($totalIncoming, 2) }}</td>
                <td class="summary-value debit" colspan="1">عليه {{ number_format($totalOutgoing, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label ar" colspan="3">إجمالي الرصيد</td>
                <td class="summary-value credit" colspan="2">{{ number_format($currentBalance, 2) . ' ' . ($account->currency?->symbol() ?? '') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>{{ $generatedAt->format('Y-m-d H:i:s') }}</td>
        </tr>
    </table>
</body>
</html>
