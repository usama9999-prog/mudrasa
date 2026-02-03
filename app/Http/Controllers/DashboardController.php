<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Shareholder;
use App\Model\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAnimals = Animal::count();
        $totalCost = Animal::sum('purchase_price');
        $butcherCost = Animal::sum('butcher_cost');
        $writingCost = Animal::sum('writing_cost');
        $fodderCost = Animal::sum('fodder_cost');
        $transportationCost = Animal::sum('transportation_cost');
        $miscellaneousCost = Animal::sum('miscellaneous_cost');
        $shareholder = Shareholder::sum('amount_submit');
        $grandTotalCost = $totalCost + $butcherCost + $writingCost + $fodderCost + $transportationCost + $miscellaneousCost;
        return view('welcome', compact(
            'totalAnimals',
            'totalCost',
            'butcherCost',
            'writingCost',
            'fodderCost',
            'transportationCost',
            'miscellaneousCost',
            'shareholder',
            'grandTotalCost'
        ));
    }

}
