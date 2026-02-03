<?php

namespace App\Http\Controllers;
use App\Models\Animal;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\AnimalsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AnimalController extends Controller
{

    public function index()
    {
       $animals = Animal::with('shareholders')
                ->orderByRaw("CAST(SUBSTRING(animal_no, 2) AS UNSIGNED)")
                ->get();

        return view('animals.index', compact('animals'));
    }

    public function exportExcel()
    {
        return Excel::download(new AnimalsExport, 'animals.xlsx');
    }

    public function exportPDF()
    {
        $animals = Animal::all();
        $pdf = PDF::loadView('animals.pdf', compact('animals'))->setPaper('a4', 'landscape');
        return $pdf->download('animals.pdf');
    }

    public function print()
    {

      $animals = Animal::with('shareholders')
    ->orderByRaw("CAST(SUBSTRING(animal_no, 2) AS UNSIGNED)")
    ->get();

        return view('animals.print', compact('animals'));
    }
    public function printSingle($id)
    {
        $ani = Animal::with('shareholders')->findOrFail($id);
        return view('animals.print', compact('ani'));
    }
    public function create()
    {
        return view('animals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_no'            => 'required|string|unique:animals,animal_no',
            'purchase_price'       => 'required|integer|min:0',
            'fodder_cost'          => 'required|integer|min:0',
            'transportation_cost'  => 'required|integer|min:0',
            'butcher_cost'         => 'required|integer|min:0',
            'writing_cost'         => 'required|integer|min:0',
            'miscellaneous_cost'   => 'required|integer|min:0',
        ]);

        Animal::create([
            'animal_no'            => $validated['animal_no'],
            'type'                 => 'cow', // You can make this dynamic later
            'purchase_price'       => $validated['purchase_price'],
            'fodder_cost'          => $validated['fodder_cost'],
            'transportation_cost'  => $validated['transportation_cost'],
            'butcher_cost'         => $validated['butcher_cost'],
            'writing_cost'         => $validated['writing_cost'],
            'miscellaneous_cost'   => $validated['miscellaneous_cost'],
        ]);

        $animals = Animal::all();
        return view('animals.index',compact('animals'));
    }



    public function show(Animal $animal)
    {
        $ani = Animal::where('id',$animal->id)->with('shareholders')->first();
        return view('animals.show', compact('ani'));
    }

    public function edit(Animal $animal)
    {
        return view('animals.edit', compact('animal'));
    }

    public function update(Request $request, Animal $animal)
    {
      $animal->animal_no = $request->input('animal_no') ?? $animal->animal_no;
        $animal->purchase_price = $request->input('purchase_price');
        $animal->fodder_cost = $request->input('fodder_cost', 0);
        $animal->transportation_cost = $request->input('transportation_cost', 0);
        $animal->butcher_cost = $request->input('butcher_cost', 0);
        $animal->writing_cost = $request->input('writing_cost', 0);
        $animal->miscellaneous_cost = $request->input('miscellaneous_cost', 0);

        $animal->save();

        // Fallback for standard (non-AJAX) form submissions
        return redirect()->route('animals.index')->with('success', 'جانور اپڈیٹ ہوگیا');
    }


    public function destroy(Animal $animal)
    {
        $animal->delete();
        return redirect()->route('animals.index');
    }


}
