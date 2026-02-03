@extends('layouts.app')

@section('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body, .container {
        font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;
        direction: rtl;
    }
    .modal-content {
        direction: rtl;
    }
    .dataTables_wrapper .dataTables_filter {
        text-align: right;
    }
    .dt-buttons {
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">حصہ داروں کی فہرست</h2>

    <div class="mb-3">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addShareholderModal">
            <i class="bi bi-plus-lg"></i> حصہ دار شامل کریں
        </button>
    </div>
<a href="{{ route('shareholders.printsss') }}" target="_blank" class="btn btn-info btn-sm">
    <i class="bi bi-printer"></i> تمام رسیدیں پرنٹ کریں
</a>
    <div class="table-responsive">
        <table id="shareholders-table" class="table table-bordered table-striped table-hover align-middle datatable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>رسید نمبر</th>
                    <th>موبائل نمبر</th>
                    <th>جانور نمبر</th>
                    <th>حصہ تعداد</th>
                    <th>جمع رقم</th>
                    <th>رقم بقایا</th>
                    <th>رقم واپسی</th>
                    <th>قیمت خرید</th>
                    <th>منڈی لکھائی + کرایہ</th>
                    <th>چارہ</th>
                    <th> اجرت قصائی</th>
                    <th>کیٹرنگ اور شاپنگ بیگ</th>
                    <th>مکمل رقم</th>
                    <th>فی حصہ</th>
                    <th>  کل حصہ</th>
                    <th>عمل</th>
                </tr>
            </thead>

            </thead>
            <tbody>
                @foreach($shareholders as $index => $shareholder)
                    @php
                        $animal = $shareholder->animal;
                        $totalAnimalCost = ($animal->purchase_price ?? 0) +
                                        ($animal->writing_cost ?? 0) +
                                        ($animal->transportation_cost ?? 0) +
                                        ($animal->fodder_cost ?? 0) +
                                        ($animal->miscellaneous_cost ?? 0);

                        $sharePrice = $totalAnimalCost / 7;
                        $expectedAmount = $shareholder->sharecount * $sharePrice;
                        $difference = $expectedAmount - $shareholder->amount_submit;

                        $baqayaDena = $difference > 0 ? number_format($difference, 2) : '';
                        $baqayaLena = $difference < 0 ? number_format(abs($difference), 2) : '';
                    @endphp
                    <tr data-id="{{ $shareholder->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $shareholder->name }}</td>
                        <td>{{ $shareholder->receipt_no }}</td>
                        <td>{{ $shareholder->mobile }}</td>
                        <td>{{ $shareholder->animal?->animal_no ?? '-' }}</td>
                        <td>{{ $shareholder->sharecount }}</td>
                        <td>{{ number_format($shareholder->amount_submit, 2) }}</td>
                        <td class="text-danger fw-bold">{{ $baqayaDena }}</td>
                        <td class="text-success fw-bold">{{ $baqayaLena }}</td>
                        <td>{{ number_format($shareholder->animal->purchase_price, 2) }}</td>
                        <td>{{ number_format(($shareholder->animal->writing_cost ?? 0) + ($shareholder->animal->transportation_cost ?? 0), 2) }}</td>
                        <td>{{ number_format($shareholder->animal->fodder_cost ?? 0, 2) }}</td>
                        <td>{{ number_format($shareholder->animal->butcher_cost ?? 0, 2) }}</td>
                        <td>{{ number_format($shareholder->animal->miscellaneous_cost ?? 0, 2) }}</td>
                        <td>{{ isset($totalAnimalCost) ? number_format($totalAnimalCost, 2) : '' }}</td>
                        <td>{{ isset($sharePrice) ? number_format($sharePrice, 2) : '' }}</td>
                        <td>{{ isset($expectedAmount) ? number_format($expectedAmount, 2) : '' }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editShareholderModal-{{ $shareholder->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('shareholders.destroy', $shareholder->id) }}" method="POST" class="d-inline" onsubmit="return confirm('کیا آپ واقعی حذف کرنا چاہتے ہیں؟');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('shareholders.partials.add')
@foreach ($shareholders as $shareholder)
    @include('shareholders.partials.edit', ['shareholder' => $shareholder, 'animals' => $animals])
@endforeach
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#shareholders-table').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/Urdu.json'
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'print', text: 'پرنٹ', exportOptions: { columns: ':not(:last-child)' } },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                exportOptions: { columns: ':not(:last-child)' },
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'right';
                    doc.styles.tableHeader.alignment = 'right';
                }
            },
            { extend: 'excelHtml5', text: 'ایکسپورٹ Excel', exportOptions: { columns: ':not(:last-child)' } }
        ]
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Add Shareholder
    $('#addShareholderForm').on('submit', function (e) {
    e.preventDefault();

    let $form = $(this);
    let $btn = $form.find('button[type="submit"]');
    $btn.prop('disabled', true).text('محفوظ کیا جا رہا ہے...');
    $('.text-danger').text(''); // Clear previous errors

    $.post($form.attr('action'), $form.serialize())
        .done(function (response) {
            alert(response.message);
            bootstrap.Modal.getInstance(document.getElementById('addShareholderModal')).hide();
            $form[0].reset();
            location.reload();
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                // Laravel validation error
                const errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    $('#error-' + field).text(messages[0]);
                });
            } else {
                alert('کچھ غلط ہوگیا، دوبارہ کوشش کریں۔');
            }
            $btn.prop('disabled', false).text('محفوظ کریں');
        });
    });


    // Populate Edit Modal
    $(document).on('click', '.btn-edit', function () {
        const shareholder = $(this).data('shareholder');
        const $modal = $('#editShareholderModal');
        $modal.find('form').attr('action', '/shareholders/' + shareholder.id);
        $.each(shareholder, (k, v) => $modal.find('[name="' + k + '"]').val(v));
        new bootstrap.Modal($modal[0]).show();
    });

    // Edit Shareholder
    $(document).on('submit', '.editShareholderForm', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $form.find('button[type="submit"]');
        let action = $form.attr('action');
        $btn.prop('disabled', true).text('اپڈیٹ کیا جا رہا ہے...');
        $form.find('.text-danger').text('');

        $.ajax({
            url: action,
            method: 'POST',
            data: $form.serialize(),
            success: function (response) {
                alert(response.message);
                bootstrap.Modal.getInstance($form.closest('.modal')[0]).hide();
                location.reload();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, val) {
                        $form.find('.error-edit-' + key).text(val[0]);
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
