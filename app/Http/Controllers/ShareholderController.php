<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shareholder;
use App\Models\Animal;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShareholdersExport;
use PDF;
class ShareholderController extends Controller
{
    public function index(Request $request)
    {
        $shareholders = Shareholder::with('animal')->get()->sortBy(function ($shareholder) {
                            return intval(ltrim($shareholder->receipt_no, '#'));
                        })->values(); 
        $animals = Animal::all();
        return view('shareholders.index',compact('shareholders','animals'));
    }

    // Export Excel
    public function exportExcel()
    {
        return Excel::download(new ShareholdersExport, 'shareholders.xlsx');
    }

    // Export PDF
    public function exportPDF()
    {
        $shareholders = Shareholder::all();
        $pdf = PDF::loadView('shareholders.pdf', compact('shareholders'));
        return $pdf->download('shareholders.pdf');
    }

    // Print View (all shareholders)
    public function print()
    {
        $shareholders = Shareholder::all();
        return view('shareholders.print', compact('shareholders'));
    }
    
    public function printReceipts()
    {
        $shareholders =  Shareholder::with('animal')->get()->sortBy(function ($shareholder) {
                            return intval(ltrim($shareholder->receipt_no, '#'));
                        })->values(); 

        // حسابات calculate کریں
        foreach ($shareholders as $shareholder) {
            $animal = $shareholder->animal;
            $totalCost = ($animal->purchase_price ?? 0) +
                        ($animal->writing_cost ?? 0) +
                        ($animal->transportation_cost ?? 0) +
                        ($animal->fodder_cost ?? 0) +
                        ($animal->miscellaneous_cost ?? 0);
            $sharePrice = $totalCost / 7;
            $shareholder->expectedAmount = $sharePrice * $shareholder->sharecount;
        }

        return view('shareholders.print', compact('shareholders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $shareholder = new Shareholder();
        $shareholder->name = $request->name;
        $shareholder->mobile = $request->mobile;
        $shareholder->receipt_no = $request->receipt_no;
        $shareholder->animal_id = $request->animal_id;
        $shareholder->sharecount = $request->sharecount;
        $shareholder->amount_submit = $request->amount_submit;
        $shareholder->address = $request->address;
        $shareholder->save();

        $shareholders = Shareholder::latest()->get();
        $animals = Animal::all();
        return view('shareholders.index',compact('shareholders','animals'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    function update(Request $request, $id)
    {
        $shareholder = Shareholder::findOrFail($id);

        $shareholder->name = $request->name;
        $shareholder->mobile = $request->mobile;
        $shareholder->receipt_no = $request->receipt_no;
        $shareholder->animal_id = $request->animal_id;
        $shareholder->sharecount = 1;
        $shareholder->amount_submit = $request->amount_submit;
        $shareholder->address = $request->address;
        $shareholder->save();

           $shareholders = Shareholder::latest()->get();
        $animals = Animal::all();
        return view('shareholders.index',compact('shareholders','animals'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shareholder = Shareholder::findOrFail($id);
        $shareholder->delete();

        return redirect()->back();
    }
}
