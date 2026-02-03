<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExamController;

Route::resource('animals', AnimalController::class);
Route::resource('shareholders', ShareholderController::class);
Route::get('/', [DashboardController::class, 'index']);



// اضافی routes for export & print
Route::get('shareholders-export-excel', [ShareholderController::class, 'exportExcel'])->name('shareholders.export.excel');
Route::get('shareholders-export-pdf', [ShareholderController::class, 'exportPDF'])->name('shareholders.export.pdf');
Route::get('/shareholders-print', [ShareholderController::class, 'printReceipts'])->name('shareholders.printsss');
Route::get('/animals/print/{id}', [AnimalController::class, 'printSingle'])->name('animals.sprint');



Route::get('/animals/export/excel', [AnimalController::class, 'exportExcel'])->name('animals.export.excel');
Route::get('/animals/export/pdf', [AnimalController::class, 'exportPDF'])->name('animals.export.pdf');
Route::get('/animals/print', [AnimalController::class, 'print'])->name('animals.print');

Route::resource('students', StudentController::class);
Route::resource('exams', ExamController::class);
Route::post('/exams/update-field', [ExamController::class, 'updateField'])->name('exams.updateField');
Route::get('/exams/sheet', [ExamController::class, 'examSheet'])->name('exams.sheets');  
Route::get('/all/student', [StudentController::class, 'classWiseList'])->name('all.student');
Route::get('/class/result', [ExamController::class, 'classResult'])->name('class.results');
// routes/web.php
Route::get('/students/classwise-list', [StudentController::class, 'classWiseList'])
    ->name('students.classwise-list');
