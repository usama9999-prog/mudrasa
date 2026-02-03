<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Exam;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('miqdar')) {
            $query->where('miqdar_e_khundgi', $request->miqdar);
        }
        $para = (int) $request->para ?? (int)30;
        if ( $para > 1 && $para <= 7) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND ?', [$para]);
        }
        elseif ( $para >= 7 && $para <= 15) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 8 AND ?', [$para]);
        } elseif ($para >= 15 && $para <= 25) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 16 AND ?', [$para]);
        } elseif ($para >= 15 && $para <= 30) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 26 AND ?', [$para]);
        } else {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND 30');
        }
        $query->orderBy('id');
        // dd( $query->count());
        $students = $query->get();
         $allClasses = Student::select('class')->distinct()->pluck('class');
        return view('students.index', compact('students','allClasses'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
         
        $request->validate([
          
            'name' => 'required|string',
            'father_name' => 'required|string',
            'class' => 'required|numeric',
            'miqdaar_e_khawandgi' => 'required|string',
            'kul_parah' => 'required|numeric',
            'description' => 'nullable|string|max:200', // ✅ Description validation
        ]);
       
        Student::create([
            'roll_no' => $request->roll_no,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'class' => $request->class, // save as 'class'
            'miqdar_e_khundgi' => $request->miqdaar_e_khawandgi,
            'kul_para' => $request->kul_parah,
            'description' => $request->description ?? '', // ✅ Save description
            'tarbiti_nisab_khuangi' => $request->tarbiti_nisab_khuangi, // ✅ New field
        ]);


        return redirect()->back()->with('success', 'طالبعلم کامیابی سے شامل کیا گیا');
    }


    public function show(string $id)
    {
        $student = Student::with('exam')->findOrFail($id);

        // ✅ sab students lekin sirf jin ke exam aur total mojood hain
        $allStudents = Student::with('exam')
            ->get()
            ->filter(fn($s) => !is_null($s->exam?->total));

        // --- Majmoi (overall) position ---
        $sortedAll = $allStudents->sortByDesc(fn($s) => $s->exam->total)->values();

        $overallPosition = $sortedAll->search(fn($s) => $s->id === $student->id);

        $overallPosition = $overallPosition !== false ? $overallPosition + 1 : null;

        // --- Category-wise position ---
        $groups = [
            '1-5'   => [1, 5],
            '6-15'  => [6, 15],
            '16-20' => [16, 20],
            '21-25' => [21, 25],
            '26-30' => [26, 30],
        ];

        $studentCategory = null;
        foreach ($groups as $key => [$min, $max]) {
            if ($student->kul_para >= $min && $student->kul_para <= $max) {
                $studentCategory = $key;
                break;
            }
        }

        $categoryPosition = null;
        if ($studentCategory) {
            $categoryStudents = $allStudents->filter(function ($s) use ($studentCategory, $groups) {
                [$min, $max] = $groups[$studentCategory];
                return $s->kul_para >= $min && $s->kul_para <= $max;
            });

            $sortedCategory = $categoryStudents->sortByDesc(fn($s) => $s->exam->total)->values();

            $categoryPosition = $sortedCategory->search(fn($s) => $s->id === $student->id);

            $categoryPosition = $categoryPosition !== false ? $categoryPosition + 1 : null;
        }

        return view('students.show', compact('student', 'overallPosition', 'categoryPosition'));
    }




    public function edit(string $id)
    {
         $student->load('exam');
        return view('students.edit', compact('student'));
    }

    public function classWiseList()
    {
   
        $classes = Student::orderBy('class')
            ->get()
            ->groupBy('class');

        return view('students.classwise_list', compact('classes'));
    }

    public function update(Request $request, string $id)
    {
        

        $student = Student::where('id', $id)->first();
        $student->update([
            'roll_no' => $request->roll_no,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'miqdar_e_khundgi' => $request->miqdaar_e_khawandgi,
            'kul_para' => $request->kul_parah,
            'class' => $request->class ?? $student->class,
            'description' => $request->description, //  Save description
            'tarbiti_nisab_khuangi' => $request->tarbiti_nisab_khuangi, //  New field
        ]);

        $students = Student::with('exam')->paginate(20);

        return redirect()->route('students.index', compact('students'))
                        ->with('success', 'طالبعلم کامیابی سے اپڈیٹ کر دیا گیا۔');
    }


    public function destroy(string $id)
    {
          $student = Student::where('id',$id)->first();
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }


     public function allStudentResult()
    {
          $students = Student::with('id',$id)->all();
     
        return redirect()->route('students.allstudent',compact('students'));
    }
}
