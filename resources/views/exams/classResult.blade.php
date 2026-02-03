@extends('layouts.app')

@section('styles')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet"/>
<style>
    .summary-boxes {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .summary-box {
        flex: 1;
        padding: 20px;
        border-radius: 10px;
        color: #fff;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }
    .zabt-box { background: #17a2b8; }
    .tajweed-box { background: #ffc107; }
    .tarbiyati-box { background: #28a745; }
    .overall-box { background: #007bff; }

    /* ✅ Print Styling */
    @media print {
        body { direction: rtl; font-family: "Noto Nastaliq Urdu", Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        table th, table td { border: 1px solid black !important; padding: 6px; }
        table th { background: #f2f2f2; font-weight: bold; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>کلاس وائز نتیجہ (100 نمبر پر)</h3>
        <div>
            <button id="printBtn" class="btn btn-primary">📄 پرنٹ</button>
            <button id="pdfBtn" class="btn btn-danger">📑 PDF</button>
        </div>
    </div>

    {{-- ✅ Overall Percentages Boxes --}}
  @php
    $totalStudents = $students->count();
    $totalZabt = $students->sum(fn($s) => $s->exam?->zabt ?? 0);
    $totalTajweed = $students->sum(fn($s) => $s->exam?->tajweed_lehja ?? 0);
    $totalTarbiyati = $students->sum(fn($s) => $s->exam?->tarbiti_nisab ?? 0);

    $avgZabt = $totalStudents ? round(($totalZabt / ($totalStudents * 60)) * 100, 2) : 0;
    $avgTajweed = $totalStudents ? round(($totalTajweed / ($totalStudents * 20)) * 100, 2) : 0;
    $avgTarbiyati = $totalStudents ? round(($totalTarbiyati / ($totalStudents * 20)) * 100, 2) : 0;

    $overall = $totalStudents ? round((($totalZabt + $totalTajweed + $totalTarbiyati) / ($totalStudents * 100)) * 100, 2) : 0;
@endphp


    <div id="summarySection" class="summary-boxes">
        <div class="summary-box zabt-box">ضبط اوسط: {{ $avgZabt }}%</div>
        <div class="summary-box tajweed-box">تجوید اوسط: {{ $avgTajweed }}%</div>
        <div class="summary-box tarbiyati-box">تربیتی نصاب اوسط: {{ $avgTarbiyati }}%</div>
        <div class="summary-box overall-box">مجموعی اوسط: {{ $overall }}%</div>
    </div>

    {{-- ✅ Filter Form --}}
    <form action="{{ route('class.results') }}" method="GET" class="filter-form row print-btn">
        <div class="col-md-3">
            <label>کلاس:</label>
            <select name="class" class="form-control">
                <option value="">--کلاس منتخب کریں--</option>
                @foreach($allClasses as $class)
                    <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                        {{ $class }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>پارہ:</label>
            <input type="number" name="para" value="{{ request('para') }}" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">فلٹر لگائیں</button>
        </div>
    </form>

    {{-- ✅ Table --}}
    <table id="studentsTable" class="table table-bordered table-striped table-hover align-middle">
        <thead>
            <tr class="text-center">
                <th>کلاس</th>
                <th>طالب علم</th>
                <th style="width: 15%;">مقدارِ خواندگی</th>
                <th>ضبط (60)</th>
                <th>تجوید و لہجہ (20)</th>
                <th>تربیتی نصاب (20)</th>
                <th>کل (100)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students->groupBy('class') as $class => $classStudents)
                @foreach($classStudents as $student)
                    @php
                        $totalMarks = ($student->exam?->zabt ?? 0) 
                                    + ($student->exam?->tajweed_lehja ?? 0) 
                                    + ($student->exam?->tarbiti_nisab ?? 0);
                    @endphp
                    <tr class="text-center">
                        <td>{{ $class }}</td>
                        <td>{{ $student->name ?? 'نامعلوم' }}</td>
                         <td>{{ $student->miqdar_e_khundgi ?? 'نامعلوم' }}</td>
                        <td>{{ $student->exam?->zabt }}</td>
                        <td>{{ $student->exam?->tajweed_lehja }}</td>
                        <td>{{ $student->exam?->tarbiti_nisab }}</td>
                        <td>{{ $totalMarks }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            @foreach($students->groupBy('class') as $class => $classStudents)
                <tr class="table-info fw-bold text-center">
                    <td colspan="2">کلاس {{ $class }} کا اوسط</td>
                    <td>{{ $classSummaries[$class]['avgZabt'] }}%</td>
                    <td>{{ $classSummaries[$class]['avgTajweed'] }}%</td>
                    <td>{{ $classSummaries[$class]['avgTarbiyati'] }}%</td>
                    <td>{{ $classSummaries[$class]['overall'] }}%</td>
                </tr>
            @endforeach
        </tfoot>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    let table = $('#studentsTable').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                name: 'printBtn',
                extend: 'print',
                text: '📄 پرنٹ',
                title: '',
                customize: function (win) {
                    $(win.document.body).prepend($('#summarySection').clone());
                    $(win.document.body).css({
                        'direction':'rtl',
                        'font-family':'Noto Nastaliq Urdu, Arial, sans-serif'
                    });
                }
            },
            {
                name: 'pdfBtn',
                extend: 'pdfHtml5',
                text: '📑 PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'کلاس وائز نتیجہ',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                    doc.styles.tableHeader.alignment = 'center';
                    doc.content.splice(0, 0, {
                        text: 'مجموعی خلاصہ:\n' +
                              'ضبط اوسط: {{ $avgZabt }}%\n' +
                              'تجوید اوسط: {{ $avgTajweed }}%\n' +
                              'تربیتی نصاب اوسط: {{ $avgTarbiyati }}%\n' +
                              'مجموعی اوسط: {{ $overall }}%\n\n',
                        alignment: 'center',
                        fontSize: 14,
                        bold: true
                    });
                }
            }
        ],
        language: {
            search: "تلاش:",
            lengthMenu: "دکھائیں _MENU_ ریکارڈز",
            info: "دکھائے جا رہے ہیں _START_ تا _END_ از _TOTAL_ ریکارڈز",
            paginate: {
                first: "پہلا",
                last: "آخری",
                next: "اگلا",
                previous: "پچھلا"
            },
            zeroRecords: "کوئی ریکارڈ نہیں ملا"
        }
    });

    // ✅ Trigger by custom buttons
    $('#printBtn').on('click', function() {
        table.button('printBtn:name').trigger();
    });
    $('#pdfBtn').on('click', function() {
        table.button('pdfBtn:name').trigger();
    });
});
</script>
@endsection
