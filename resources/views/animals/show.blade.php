@extends('layouts.app')
@section('title', 'جانور نمبر: ' . $ani->animal_no)
@section('styles')
<style>
    body {
        font-family: 'Noto Nastaliq Urdu', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        direction: rtl;
        text-align: right;
        background-color: #f9f9f9;
    }

    .urdu-heading {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        color: #1a237e;
    }

    .custom-table {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        font-size: 12px;
    }

    .custom-table th, .custom-table td {
        border: 1px solid #adb5bd !important;
        vertical-align: middle;
        padding: 6px !important;
    }

    .custom-table th {
        background-color: #e3f2fd;
        color: #0d47a1;
        font-weight: bold;
    }

    .animal-image {
        display: block;
        margin: 20px auto 0;
        max-width: 200px;
        border: 2px solid #ddd;
        border-radius: 10px;
    }

    .no-print {
        display: block;
    }
.custom-table {
    table-layout: fixed;
    width: 100%;
}
   @media print {
    thead {
        display: table-header-group;
    }

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>
@endsection

@section('content')
<div class="container py-4" dir="rtl">
    <div class="text-center my-4">
        <h2 class="urdu-heading mb-2">قربانی 2025</h2>
        <h4 class="urdu-heading mb-1">مدرسہ محمدیہ دارالقرآن</h4>
        <p class="urdu-heading small">جامع مسجد حسن، نیو سٹی فیز 2، واہ کینٹ</p>
    </div>



    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>رسید نمبر</th>
                    <th>موبائل نمبر</th>
                    <th>حصہ تعداد</th>
                    <th>جمع رقم</th>
                    <th>رقم بقایا</th>
                    <th>رقم واپسی</th>
                    <th>قیمت خرید</th>
                    <th>منڈی لکھائی + کرایہ</th>
                    <th>چارہ</th>
                    <th>اجرت قصائی</th>
                    <th>کیٹرنگ + بیگ</th>
                    <th>مکمل رقم</th>
                    <th>فی حصہ</th>
                    <th>آپ کا کل حصہ</th>
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

                        $totalAnimalCost = $purchase_price + $writing + $transport + $fodder + $butcher + $misc;
                        $sharePrice = $totalAnimalCost / 7;
                        $expectedAmount = $shareholder->sharecount * $sharePrice;
                        $difference = $expectedAmount - $shareholder->amount_submit;

                        $baqayaDena = $difference > 0 ? number_format($difference, 2) : '';
                        $baqayaLena = $difference < 0 ? number_format(abs($difference), 2) : '';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $shareholder->name }}</td>
                        <td>{{ $shareholder->receipt_no }}</td>
                        <td>{{ $shareholder->mobile }}</td>
                        <td>{{ $shareholder->sharecount }}</td>
                        <td>{{ number_format($shareholder->amount_submit, 2) }}</td>
                        <td class="text-danger fw-bold">{{ $baqayaDena }}</td>
                        <td class="text-success fw-bold">{{ $baqayaLena }}</td>

                        {{-- Multiply each per-share cost by sharecount --}}
                        <td>{{ number_format(($purchase_price / 7) * $shareholder->sharecount, 2) }}</td>
                        <td>{{ number_format((($writing + $transport) / 7) * $shareholder->sharecount, 2) }}</td>
                        <td>{{ number_format(($fodder / 7) * $shareholder->sharecount, 2) }}</td>
                        <td>{{ number_format(($butcher / 7) * $shareholder->sharecount, 2) }}</td>
                        <td>{{ number_format(($misc / 7) * $shareholder->sharecount, 2) }}</td>

                        {{-- Total animal cost (unchanged, or divide/multiply if needed) --}}
                        <td>{{ number_format($totalAnimalCost/7* $shareholder->sharecount, 2) }}</td>

                        {{-- Share price (multiplied by sharecount) --}}
                        <td>{{ number_format($sharePrice * $shareholder->sharecount, 2) }}</td>

                        {{-- Expected amount (unchanged or also multiplied if it’s per share) --}}
                        <td>{{ number_format($expectedAmount, 2) }}</td>
                    </tr>


                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
@endsection
