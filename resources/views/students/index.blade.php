@extends('layouts.app')

@section('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

<style>
    body, .container {
        font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;
        direction: rtl;
    }
    .modal-content {
        direction: rtl;
    }
    .dataTables_wrapper .dataTables_paginate {
        float: left !important;
    }
    .dataTables_wrapper .dataTables_filter {
        float: right !important;
        text-align: right !important;
    }
    td.description {
        max-width: 300px;
        white-space: normal !important;
        word-wrap: break-word;
    }
</style>
@endsection

@section('content')
<div class="container py-4 text-start">
    <h2 class="mb-4 fw-bold text-start">طلباء کی فہرست</h2>

    <div class="mb-3 text-start" dir="ltr">
        <div class="d-inline-flex gap-2 flex-wrap justify-content-start">
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-lg"></i> طالب علم شامل کریں
            </button>
        </div>
    </div>
 <form action="{{ route('students.index') }}" method="GET" class="filter-form row  print-btn">
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
    <a href="{{ route('exams.sheets') }}" class="btn btn-sm btn-success mb-3">امتحانی شیٹ بنائیں</a>
     <a href="{{ route('all.student') }}" class="btn btn-sm btn-success mb-3">     رزلٹ  شیٹ بنائیں
 </a>
    <div class="table-responsive">
        <table id="students-table" 
               class="table table-bordered  table-striped table-hover align-middle" 
               style="width: 100%;">
            <thead>
                <tr class="text-center">
                    <th style="width: 10%;">رول نمبر</th>
                    <th style="width: 10%;">نام</th>
                    <th style="width: 15%;">والد کا نام</th>
                    <th style="width: 15%;">مقدارِ خواندگی</th>
                    <th style="width: 10%;">کل پارہ</th>
                    <th style="width: 15%;">تربیتی نصاب خواندگی</th>
                    <th style="width: 30%;">تفصیل</th>
                    <th style="width: 10%;">عمل</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr class="text-center">
                        <td>{{ $student->roll_no }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->father_name }}</td>
                        <td>{{ $student->miqdar_e_khundgi }}</td>
                        <td>{{ $student->kul_para }}</td>
                        <td>{{ $student->tarbiti_nisab_khuangi }}</td>
                        <td class="text-start description">
                            {{ $student->description }}
                        </td>
                       <td>
                            <!-- Show Button -->
                            <a href="{{ route('students.show', $student->id) }}" 
                            class="btn btn-xs btn-info custom-btn">
                                دیکھیں
                            </a>

                            <!-- Edit Button -->
                            <a href="#" 
                            class="btn btn-xs btn-primary custom-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#editStudentModal{{ $student->id }}">
                                تدوین
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('students.destroy', $student->id) }}" 
                                method="POST" 
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-xs btn-danger custom-btn"
                                        onclick="return confirm('یقیناً حذف کریں؟')">
                                    حذف
                                </button>
                            </form>
                        </td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('students.partials.add')

@foreach ($students as $student)
    @include('students.partials.edit', ['student' => $student])
@endforeach
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- ✅ Buttons plugins -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    $('#students-table').DataTable({
        destroy: true, // ✅ Fix reinitialise error
        responsive: true,
        ordering: false,
         sorting: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: '🖨 پرنٹ',
                className: 'btn btn-sm btn-secondary',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'excelHtml5',
                text: '📊 ایکسل',
                className: 'btn btn-sm btn-success',
                exportOptions: { columns: ':not(:last-child)' }
            },
          
            {
                extend: 'colvis',
                text: '👁 شو / چھپائیں',
                className: 'btn btn-sm btn-dark'
            }
        ],
        language: {
            processing: "براہِ کرم انتظار کریں...",
            search: "تلاش:",
            searchPlaceholder: "نام یا رول نمبر...",
            lengthMenu: "دکھائیں _MENU_ ریکارڈز",
            zeroRecords: "کوئی ریکارڈ نہیں ملا",
            info: "دکھائے جا رہے ہیں _START_ تا _END_ از _TOTAL_ ریکارڈز",
            infoEmpty: "کوئی ریکارڈ دستیاب نہیں",
            infoFiltered: "(کل _MAX_ ریکارڈز میں سے چھانا گیا)",
            paginate: {
                first: "پہلا",
                last: "آخری",
                next: "اگلا",
                previous: "پچھلا"
            }
        }
    });
});
</script>
@endsection
