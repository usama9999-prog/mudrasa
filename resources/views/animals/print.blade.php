<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جانور نمبر: {{ $ani->animal_no }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Nastaliq Urdu', 'Segoe UI', Tahoma, sans-serif;
            direction: rtl;
            font-size: 11px;
            background-color: #fff;
        }

        .page {
            width: 325mm;
            min-height: 297mm;
            padding: 10mm 15mm;
            margin: auto;
        }

        .card-header {
            background-color: #004085;
            color: white;
            font-weight: bold;
            padding: 6px 12px;
            font-size: 20px;
        }
        .card-body {
           
            font-size: 20px;
        }
        .info-label {
            font-weight: bold;
        }

        .table th, .table td {
            padding: 4px;
            font-size: 18px;
            vertical-align: middle;
        }

        .table th {
            background-color: #e9ecef;
        }

        .footer-sign {
            border-top: 1px solid #000;
            width: 70%;
            margin-top: 30px;
            font-size: 18px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
            }

            .page {
                box-shadow: none;
                padding: 0;
            }

            .card {
                border: 1px solid #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="page">

        <!-- Header -->
        <div class="text-center mb-2">
            <h3 class="fw-bold mb-0">مدرسہ محمدیہ دارالقرآن</h3>
            <h5 class="text-muted">قربانی 2025</h5>
            <p class="mb-1">جامع مسجد حسن، نیو سٹی فیز 2، واہ کینٹ</p>
            <hr>
        </div>

        <!-- Animal Info -->
        <div class="card mb-2">
            <div class="card-header">جانور کی معلومات</div>
            <div class="card-body pb-1 pt-2">
                <div class="row">
                    
    <div class="col-md-4"><span class="info-label">جانور نمبر:</span> {{ $ani->animal_no }}</div>
    
    <div class="col-md-4"><span class="info-label">خرید:</span> {{ number_format($ani->purchase_price ?? 0, 0) }} روپے</div>

    <div class="col-md-4"><span class="info-label">لکھائی/کرایہ:</span> {{ number_format(($ani->writing_cost ?? 0) + ($ani->transportation_cost ?? 0), 0) }} روپے</div>
    <div class="col-md-4"><span class="info-label">چارہ:</span> {{ number_format($ani->fodder_cost ?? 0, 0) }} روپے</div>
    <div class="col-md-4"><span class="info-label">کیٹرنگ + بیگ:</span> {{ number_format($ani->miscellaneous_cost ?? 0, 0) }} روپے</div>
    <div class="col-md-4"><span class="info-label">کل لاگت:</span> {{ number_format(
        ($ani->purchase_price ?? 0) +
        ($ani->writing_cost ?? 0) +
        ($ani->transportation_cost ?? 0) +
        ($ani->fodder_cost ?? 0) +
        ($ani->miscellaneous_cost ?? 0), 2) }} روپے</div>
                </div>
            </div>
        </div>

        <!-- Shareholder Table -->
        <div class="card">
            <div class="card-header">شیئر ہولڈرز کی تفصیل</div>
            <div class="card-body p-0">
                <table class="table table-bordered text-center m-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>رسید</th>
                            <th>موبائل</th>
                            <th>حصے</th>
                            <th>جمع رقم</th>
                            <th>بقایا</th>
                            <th>واپسی</th>
                            <th>خرید</th>
                            <th>کرایہ/لکھائی</th>
                            <th>چارہ</th>
                            <th>قصائی</th>
                            <th>دیگر</th>
                            <th>کل</th>
                            <th>فی حصہ</th>
                            <th>کل حصہ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ani->shareholders as $index => $shareholder)
                            @php
                                $animal = $shareholder->animal;
                                $purchase_price = $animal->purchase_price ?? 0;
                                $writing = $animal->writing_cost ?? 0;
                                $transport = $animal->transportation_cost ?? 0;
                                $fodder = $animal->fodder_cost ?? 0;
                                $butcher = $animal->butcher_cost ?? 0;
                                $misc = $animal->miscellaneous_cost ?? 0;

                                $total = $purchase_price + $writing + $transport + $fodder + $butcher + $misc;
                                $perShare = $total / 7;
                                $expected = $perShare * $shareholder->sharecount;
                                $diff = $expected - $shareholder->amount_submit;
                                $baqaya = $diff > 0 ? number_format($diff, 2) : '';
                                $wapasi = $diff < 0 ? number_format(abs($diff), 2) : '';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $shareholder->name }}</td>
                                <td>{{ $shareholder->receipt_no }}</td>
                                <td>{{ $shareholder->mobile }}</td>
                                <td>{{ $shareholder->sharecount }}</td>
                                <td>{{ number_format($shareholder->amount_submit, 2) }}</td>
                                <td class="text-danger">{{ $baqaya }}</td>
                                <td class="text-success">{{ $wapasi }}</td>
                                <td>{{ number_format(($purchase_price / 7) * $shareholder->sharecount, 0) }}</td>
                                <td>{{ number_format((($writing + $transport) / 7) * $shareholder->sharecount, 0) }}</td>
                                <td>{{ number_format(($fodder / 7) * $shareholder->sharecount, 0) }}</td>
                                <td>{{ number_format(($butcher / 7) * $shareholder->sharecount, 0) }}</td>
                                <td>{{ number_format(($misc / 7) * $shareholder->sharecount, 0) }}</td>
                                <td>{{ number_format(($total / 7) * $shareholder->sharecount, 0) }}</td>
                                <td>{{ number_format($perShare, 0) }}</td>
                                <td>{{ number_format($expected, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="row mt-3">
            <div class="col-6 text-end info-label">
                <p>دستخط صدر:</p>
                <div class="footer-sign ms-auto"></div>
            </div>
            <div class="col-6 text-start info-label">
                <p>دستخط منتظم:</p>
                <div class="footer-sign me-auto"></div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="text-center mt-3 no-print">
            <button class="btn btn-primary" onclick="window.print()">پرنٹ کریں</button>
        </div>
    </div>
</body>
</html>


