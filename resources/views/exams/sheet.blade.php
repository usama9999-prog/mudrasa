@extends('layouts.app')

@section('content')

{{-- Urdu Font --}}
<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Noto Nastaliq Urdu', serif;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 100%;
        margin: auto;
    }

    .print-btn {
        margin-bottom: 20px;
    }

    .exam-page {
        width: 100%;
        height: 100%;
        page-break-after: always;
        background: #fff;
        padding: 10px 20px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }

    .exam-page::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300px;   /* watermark size */
        height: 300px;
        background: url('{{ asset('image/logo.jpeg') }}') no-repeat center center;
        background-size: contain;
        opacity: 0.15;   /* light transparency */
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
    }

    .exam-page > * {
        position: relative;
        z-index: 1;
    }

    /* مدرسہ کا نام */
    .madarsa-name {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        border-bottom: 2px solid #000;
        padding-bottom: 5px;
    }

    /* Student blocks wrapper */
    .students-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .student-block {
        height: 15.5%; /* 6 blocks per page */
        border: 1px solid #000;
        padding: 6px;
        border-radius: 6px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        margin-bottom: 4px;
    }

    .student-header {
        width: 100%;
        border-bottom: 1px solid #000;
        margin-bottom: 4px;
        font-size: 11px;
    }

    .student-header td, 
    .student-header th {
        padding: 2px 4px;
        font-size: 11px;
    }

    .exam-table {
        width: 100%;
        border-collapse: collapse;
        flex: 1;
    }

    .exam-table th, .exam-table td {
        border: 1px solid #000;
        padding: 2px;
        font-size: 10px;
        text-align: center;
    }

    .exam-table th {
        background: #f0f0f0;
        font-weight: bold;
    }

    .details-cell {
        text-align: right;
        font-size: 9px;
        line-height: 1.2;
    }

    .signature {
        margin-top: 0px;
        text-align: left;
        font-size: 10px;
    }

    @media print {
        body {
            background: #fff;
        }
        .print-btn {
            display: none;
        }
        .exam-page {
            page-break-after: always;
        }
        @page {
            size: A4 landscape;
            margin: 0mm;
        }
        header, footer, nav, .navbar, .sidebar, .app-header, .app-footer {
            display: none !important;
            visibility: hidden !important;
        }
    }
</style>

<form action="{{ route('exams.sheets') }}" method="GET" class="filter-form row  print-btn">
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

<div class="container">
    <button class="btn btn-primary print-btn" onclick="window.print()">پرنٹ شیٹ</button>

    {{-- Chunk 6 students per page --}}
    @foreach($students->chunk(5) as $pageStudents)
        <div class="exam-page print-area">
           
            <div class="madarsa-name">
                <img src="{{ asset('image/jamia5.png') }}" alt="Logo" style="height:40px;">
            </div>

            <div class="students-wrapper">
                @foreach($pageStudents as $student)
                 @php
                    $para = (int) ($student->kul_para ?? 0);
                    $questions = [];

                    if ($para <= 7) {
                        $questions = [25,25];
                    } elseif ($para <= 15) {
                        $questions = [17,17,16];
                    } else {
                        $questions = [13,13,12,12];
                    }
                @endphp



                    <div class="student-block">
                        {{-- Student Header --}}
                        <table class="student-header" width="100%">
                            <tr>
                                <th>نام</th>
                                <td>{{ $student->name }}</td>
                                <th>ولدیت</th>
                                <td>{{ $student->father_name }}</td>
                                <th>مقدار خواندگی</th>
                                <td>{{ $student->miqdar_e_khundgi ?? '-' }}</td>
                                <th>پارہ</th>
                                <td>{{ $student->kul_para ?? '-' }}</td>
                                <th>تفصیل</th>
                                <td>{{ $student->description ?? '-' }}</td>
                            </tr>
                        </table>

                        {{-- Exam Table --}}
                        <table class="exam-table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:12%">تفصیل</th>

                                    @foreach ($questions as $index => $marks)
                                        <th>سوال {{ $index + 1 }} <small>({{ $marks }})</small></th>
                                    @endforeach

                                    <th>ضبط <small>(50)</small></th>
                                    <th>تجوید <small>(20)</small></th>
                                    <th>لہجہ <small>(10)</small></th>
                                 
                                    <th>کل نمبر <small>(80)</small></th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- Marks Row --}}
                                <tr>
                                    <td rowspan="2">1</td>
                                    <td>نمبروں کیلئے</td>

                                    @foreach ($questions as $q)
                                        <td>____</td>
                                    @endforeach

                                    <td>____</td>
                                    <td>____</td>
                                    <td>____</td>
                                    <td>____</td> {{-- کل نمبر --}}
                                </tr>

                                {{-- Question Detail Row --}}
                                <tr>
                                    <td>سوال کی تفصیل</td>

                                  @foreach ($questions as $q)
                                        <td class="details-cell" style="white-space: nowrap; font-size:10px;">
                                            پارہ: ____ &nbsp; سورۃ: ____ &nbsp; آیت: ____
                                        </td>
                                    @endforeach

                                    {{-- ضبط، تجوید، لہجہ، تربیتی نصاب، کل نمبر --}}
                                    <td colspan="5"></td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Examiner Section --}}
                        <div style="display:flex; margin-top:0px;">
                            <div style="flex:70%; text-align:right; border-top:1px dashed #000; padding-top:2px; font-size:10px;">
                                رائے ممتحن: ________________________________________
                            </div>
                            <div style="flex:30%; text-align:left; border-top:1px dashed #000; padding-top:0px; font-size:10px;">
                                دستخط ممتحن: __________________
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endsection
