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
</style>
@endsection

@section('content')
<div class="container py-4 text-start">
    <h2 class="mb-4 fw-bold text-start">جانوروں کی فہرست</h2>

    <div class="mb-3 text-start" dir="ltr">
        <div class="d-inline-flex gap-2 flex-wrap justify-content-start">

       
<a href="{{ route('animals.export.pdf') }}" target="_blank" class="btn btn-success">
    تمام جانوروں کی پرنٹ رپورٹ
</a>

            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addAnimalModal">
                <i class="bi bi-plus-lg"></i> جانور شامل کریں
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="animals-table" class="table table-bordered datatable table-striped table-hover align-middle" style="width: 100%;">
    <thead class="table-light text-start">
        <tr>
            <th>#</th>
            <th>جانور نمبر</th>
            <th>حصہ تعداد</th>

            <th>جمع رقم</th> <!-- moved here -->
            <th>قیمت خرید</th>
            <th>منڈی لکھائی + کرایہ</th>
            <th>چارہ</th>
            <th>اجرت قصائی</th>
            <th>کیٹرنگ + بیگ</th>
            <th>رقم بقایا</th>
            <th>رقم واپسی</th>
            <th>مجموعہ</th>
            <th>عمل</th>
        </tr>
    </thead>
    <tbody class="text-end">
        @foreach ($animals as $index => $animal)
        @php
            $totalCost = 
                ($animal->purchase_price ?? 0) +
                ($animal->writing_cost ?? 0) +
                ($animal->transportation_cost ?? 0) +
                ($animal->fodder_cost ?? 0) +
                ($animal->butcher_cost ?? 0) +
                ($animal->miscellaneous_cost ?? 0);

            $totalShareholderAmount = $animal->shareholders->sum('amount_submit');
            $difference = $totalShareholderAmount - $totalCost;
        @endphp
        <tr data-id="{{ $animal->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $animal->animal_no }}</td>
            <td>{{ $animal->shareholders->sum('sharecount') ?? '-' }}</td>

            <!-- جمع رقم: Total Cost -->
             <td>{{ number_format($totalShareholderAmount, 2) }}</td>
            <td>{{ number_format($animal->purchase_price, 2) }}</td>
            <td>{{ number_format(($animal->writing_cost ?? 0) + ($animal->transportation_cost ?? 0), 2) }}</td>
            <td>{{ number_format($animal->fodder_cost ?? 0, 2) }}</td>
            <td>{{ number_format($animal->butcher_cost ?? 0, 2) }}</td>
            <td>{{ number_format($animal->miscellaneous_cost ?? 0, 2) }}</td>

            <!-- رقم بقایا -->
            <td> @if($difference < 0)
                    {{ number_format($difference, 2) }}
                @else
                    -
                @endif</td>

            <!-- رقم واپسی -->
            <td>
                @if($difference > 0)
                    {{ number_format($difference, 2) }}
                @else
                    -
                @endif
            </td>

            <!-- مجموعہ -->
            <td>
               
                {{ number_format(abs($totalCost), 2) }}
                
            </td>

            <!-- Actions -->
            <td class="text-center">
                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAnimalModal-{{ $animal->id }}">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="{{ route('animals.sprint', $animal->id) }}" class="btn btn-sm btn-success" target="_blank">
    <i class="bi bi-printer"></i> پرنٹ
</a>
                    <a href="{{ route('animals.show', $animal->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                <form action="{{ route('animals.destroy', $animal->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('کیا آپ واقعی حذف کرنا چاہتے ہیں؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
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

    @include('animals.partials.add')
    @foreach ($animals as $animal)
        @include('animals.partials.edit', ['animal' => $animal])
    @endforeach
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#animals-table').DataTable({
        language: {
            processing: "براہِ کرم انتظار کریں...",
            search: "تلاش:",
            searchPlaceholder: "جانور نمبر یا دیگر...",
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
        },
        responsive: true
    });

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    });

    $('#addAnimalForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $form.find('button[type="submit"]');
        $btn.prop('disabled', true).text('محفوظ کیا جا رہا ہے...');
        $('.text-danger').text('');

        $.post($form.attr('action'), $form.serialize())
        .done(response => {
            alert(response.message);
            bootstrap.Modal.getInstance(document.getElementById('addAnimalModal')).hide();
            $form[0].reset();
            location.reload();
        })
        .fail(xhr => {
            if (xhr.status === 422) {
                $.each(xhr.responseJSON.errors, function (key, val) {
                    $('#error-' + key).text(val[0]);
                });
            } else {
                alert('کچھ غلط ہوگیا، دوبارہ کوشش کریں۔');
            }
            $btn.prop('disabled', false).text('محفوظ کریں');
        });
    });

    $(document).on('click', '.edit-animal-btn', function () {
        const animal = $(this).data('animal');
        const $modal = $('#editAnimalModal');
        $modal.find('form').attr('action', '/animals/' + animal.id);
        $.each(animal, function (key, val) {
            $modal.find('[name="' + key + '"]').val(val);
        });
        new bootstrap.Modal($modal[0]).show();
    });

    $('#editAnimalForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $form.find('button[type="submit"]');
        $btn.prop('disabled', true).text('اپڈیٹ کیا جا رہا ہے...');
        $('.text-danger').text('');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function (response) {
                alert(response.message);
                bootstrap.Modal.getInstance(document.getElementById('editAnimalModal')).hide();
                location.reload();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, val) {
                        $('#error-edit-' + key).text(val[0]);
                    });
                } else {
                    alert('کچھ غلط ہوگیا، دوبارہ کوشش کریں۔');
                }
                $btn.prop('disabled', false).text('اپڈیٹ کریں');
            }
        });
    });
});
</script>
@endsection
