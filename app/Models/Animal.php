<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = [
        'animal_no',
        'type',
        'purchase_price',
        'fodder_cost',
        'transportation_cost',
        'butcher_cost',
        'writing_cost',
        'miscellaneous_cost',
    ];


    public function shareholders()
    {
        return $this->hasMany(Shareholder::class);
    }


}
