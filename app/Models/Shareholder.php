<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    protected $guarded = [];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }


}
