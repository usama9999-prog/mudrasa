<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'roll_no', 'name', 'father_name', 'miqdar_e_khundgi','tarbiti_nisab_khuangi', 'kul_para','class','description'
    ];

    public function exam()
    {
        return $this->hasOne(Exam::class);
    }
}