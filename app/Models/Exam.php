<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'student_id',
        'zabt',
        'tajweed_lehja',
        'tarbiti_nisab',
        'guzashta_jaiza',
        'hazri',
        'lehja',
        'tarjuma',
        'percentage',
        'total',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Optional: calculate total automatically on save
    protected static function booted()
    {
        static::saving(function ($exam) {
            $exam->total =
                ($exam->zabt ?? 0) +
                ($exam->tajweed_lehja ?? 0) +
                ($exam->tarbiti_nisab ?? 0) +
                ($exam->guzashta_jaiza ?? 0) +
                ($exam->hazri ?? 0) +
                ($exam->tarjuma ?? 0);
        });
    }

    
}
