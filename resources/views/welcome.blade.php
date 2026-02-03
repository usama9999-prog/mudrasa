@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        {{-- Total Animals --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-primary border-4">
                <div class="card-body text-center">
                    <h6 class="text-primary fw-semibold"> مجموعی جانور</h6>
                    <h3 class="fw-bold">{{ $totalAnimals ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Total Purchase Cost --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-success border-4">
                <div class="card-body text-center">
                    <h6 class="text-success fw-semibold"> میزان خرچ</h6>
                    <h3 class="fw-bold">Rs {{ number_format($grandTotalCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-success border-4">
                <div class="card-body text-center">
                    <h6 class="text-success fw-semibold">کل خریداری</h6>
                    <h3 class="fw-bold">Rs {{ number_format($totalCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>
        {{-- Butcher Cost --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-warning border-4">
                <div class="card-body text-center">
                    <h6 class="text-warning fw-semibold"> قصائی کے اخراجات</h6>
                    <h3 class="fw-bold">Rs {{ number_format($butcherCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>

        {{-- Writing + Transport Cost --}}
        <!-- <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-danger border-4">
                <div class="card-body text-center">
                    <h6 class="text-danger fw-semibold">لکھائی کے اخراجات</h6>
                    <h3 class="fw-bold">Rs {{ number_format($writingCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div> -->

        {{-- Fodder Cost --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-info border-4">
                <div class="card-body text-center">
                    <h6 class="text-info fw-semibold"> چارے کے اخراجات</h6>
                    <h3 class="fw-bold">Rs {{ number_format($fodderCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>

        {{-- Transportation Cost --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-secondary border-4">
                <div class="card-body text-center">
                    <h6 class="text-secondary fw-semibold"> کرایہ کے اخراجات</h6>
                    <h3 class="fw-bold">Rs {{ number_format($transportationCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>

        {{-- Miscellaneous Cost --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-dark border-4">
                <div class="card-body text-center">
                    <h6 class="text-dark fw-semibold">کیٹرنگ اور شاپنگ بیگ</h6>
                    <h3 class="fw-bold">Rs {{ number_format($miscellaneousCost ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>

         <div class="col-lg-3 col-md-6">
            <div class="card shadow border-start border-dark border-4">
                <div class="card-body text-center">
                   <h6 class="text-dark fw-semibold">کل جمع رقم</h6>

                    <h3 class="fw-bold">Rs {{ number_format($shareholder ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Buttons --}}
    <div class="row mt-5">
        <div class="col-md-6 mb-3">
            <a href="{{ route('animals.index') }}" class="btn btn-outline-primary w-100 py-3 fw-bold">
                🐄 Manage Animals / جانور منیج کریں
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('shareholders.index') }}" class="btn btn-outline-success w-100 py-3 fw-bold">
                👥 Manage Participants / حصہ دار منیج کریں
            </a>
        </div>
    </div>
</div>
@endsection
