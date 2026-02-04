@extends('layouts.app')

@section('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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

    table th, table td {
        vertical-align: middle !important;
        text-align: center;
        font-weight: 600;
    }

    /* ✅ Print Styling */
    @media print {
        table {
            border-collapse: collapse !important;
            width: 100% !important;
            font-size: 14px;
        }
        table th, table td {
            border: 1px solid black !important;
            padding: 6px !important;
        }
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate,
        .btn,
        .print-btn {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📘 امتحانات</h3>
        <a href="{{ route('exams.create') }}" class="btn btn-primary print-btn">
            <i class="bi bi-plus-circle"></i> نیا امتحان
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- ✅ Filter -->
    <form method="GET" class="row mb-4 print-btn">
        <div class="col-md-3">
            <label>کلاس</label>
            <select name="class" class="form-control">
                <option value="">تمام</option>
                @foreach($allClasses as $class)
                    <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                        {{ $class }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>پارہ</label>
            <input type="number" name="para" value="{{ request('para') }}" class="form-control">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-success w-100">
                <i class="bi bi-funnel"></i> فلٹر
            </button>
        </div>
    </form>

    <!-- ✅ Table -->
    <div class="table-responsive">
        <table id="students-table" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>طالب علم</th>
                    <th>کلاس</th>
                    <th>مقدارِ خواندگی</th>
                    <th>کل پارہ</th>
                    <th>ضبط</th>
                    <th>تجوید</th>
                    <th>لہجہ</th>
                    <th>تربیتی نصاب</th>
                    <th>گزشتہ جائزہ</th>
                    <th>حاضری</th>
                    <th>ترجمہ</th>
                    <th>کل</th>
                    <th>فیصد</th>
                    <th>پوزیشن</th>
                    <th>گریڈ</th>
                    <th class="print-btn">عمل</th>
                </tr>
            </thead>

            <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->class }}</td>
                    <td>{{ $student->miqdar_e_khundgi }}</td>
                    <td>{{ $student->kul_para }}</td>

                    <td>{{ $student->exam?->zabt ?? '-' }}</td>
                    <td>{{ $student->exam?->tajweed_lehja ?? '-' }}</td>
                    <td>{{ $student->exam?->lehja ?? '-' }}</td>
                    <td>{{ $student->exam?->tarbiti_nisab ?? '-' }}</td>
                    <td>{{ $student->exam?->guzashta_jaiza ?? '-' }}</td>
                    <td>{{ $student->exam?->hazri ?? '-' }}</td>
                    <td>{{ $student->exam?->tarjuma ?? '-' }}</td>

                    <td>{{ $student->exam?->total ?? '-' }}</td>
                    <td>{{ $student->exam?->percentage ?? '-' }}%</td>

                    <td>
                        @if($student->category_position == 1)
                            <span class="badge bg-success">🥇 اول</span>
                        @elseif($student->category_position == 2)
                            <span class="badge bg-primary">🥈 دوم</span>
                        @elseif($student->category_position == 3)
                            <span class="badge bg-warning text-dark">🥉 سوم</span>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        <span class="badge bg-dark">{{ $student->grade ?? '-' }}</span>
                    </td>

                    <td class="print-btn">
                        <a href="#" class="btn btn-sm btn-outline-primary"
                           data-bs-toggle="modal"
                           data-bs-target="#editExamModal{{ $student->id }}">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('students.destroy',$student) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('حذف کریں؟')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Edit Modals -->
@foreach($students as $student)
    @include('exams.partials.edit',['student'=>$student])
@endforeach
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
$(function () {
    $('#students-table').DataTable({
        ordering: false,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'print', text: '🖨 پرنٹ', className: 'btn btn-secondary btn-sm' },
            { extend: 'excelHtml5', text: '📊 ایکسل', className: 'btn btn-success btn-sm' }
        ],
        language: {
            search: "تلاش:",
            lengthMenu: "دکھائیں _MENU_",
            info: "_TOTAL_ میں سے _START_ تا _END_",
            paginate: { next: "اگلا", previous: "پچھلا" },
            zeroRecords: "کوئی ریکارڈ نہیں"
        }
    });
});
</script>
@endsection
