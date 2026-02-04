<?php

// app/Http/Controllers/ExamController.php
namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
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
    if ($para > 1 && $para <= 2) {
        $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND ?', [$para]);
    } elseif ($para >= 2 && $para <= 7) {
        $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 3 AND ?', [$para]);
    } elseif ($para >= 7 && $para <= 13) {
        $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 8 AND ?', [$para]);
    } elseif ($para >= 13 && $para <= 30) {
        $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 14 AND ?', [$para]);
    } else {
        $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND 30');
    }

    $query->orderBy('id');
    $students = $query->get();

    // صرف وہ طلبہ جن کے exam marks موجود ہیں
    $rankableStudents = $students->filter(fn($s) => !is_null($s->exam?->total));

    // --- Category-wise Position Only ---
    $groups = [
        '1-7'   => [1, 7],
        '8-13'  => [8, 13],
        '14-29' => [14, 30]
    ];

    foreach ($students as $student) {
        $student->category_position = null;

        foreach ($groups as $key => [$min, $max]) {
            if ($student->kul_para >= $min && $student->kul_para <= $max) {
                $categoryStudents = $rankableStudents->filter(function ($s) use ($min, $max) {
                    return $s->kul_para >= $min && $s->kul_para <= $max;
                });

                $sortedCategory = $categoryStudents->sortByDesc(fn($s) => $s->exam->total)->values();

                $catRanks = [];
                $rank = 0;
                $lastScore = null;
                foreach ($sortedCategory as $s) {
                    if ($s->exam->total !== $lastScore) {
                        $rank++;
                        $lastScore = $s->exam->total;
                    }
                    $catRanks[$s->id] = $rank;
                }

                $student->category_position = $catRanks[$student->id] ?? null;
                break;
            }
        }

        // --- Grade Calculation ---
        $percentage = $student->exam?->percentage ?? 0;
        if ($percentage >= 90) {
            $grade = "A+";
        } elseif ($percentage >= 85) {
            $grade = "A";
        } elseif ($percentage >= 80) {
            $grade = "B+";
        } elseif ($percentage >= 75) {
            $grade = "B";
        } elseif ($percentage >= 70) {
            $grade = "C+";
        } elseif ($percentage >= 65) {
            $grade = "C";
        } elseif ($percentage >= 60) {
            $grade = "D";
        } else {
            $grade = "Fail";
        }
        $student->grade = $grade;
    }

    $allClasses = Student::select('class')->distinct()->pluck('class');
    return view('exams.index', compact('students','allClasses'));
}

    public function allstudent(Request $request){
     $query = Student::with('exam');

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('miqdar')) {
            $query->where('miqdar_e_khundgi', $request->miqdar);
        }

        if ($request->filled('para')) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) <= ?', [$request->para]);
        }
        $query->orderBy('id','ASC');
        $students = $query->get();

        // Sirf jin ke exam marks mojood hain
        $rankableStudents = $students->filter(fn($s) => !is_null($s->exam?->total));

        // --- Overall ranking (competition style: same marks = same rank) ---
        $sortedAll = $rankableStudents->sortByDesc(fn($s) => $s->exam->total)->values();
        $overallRanks = [];
        $rank = 0;
        $lastScore = null;
        foreach ($sortedAll as $s) {
            if ($s->exam->total !== $lastScore) {
                $rank++;
                $lastScore = $s->exam->total;
            }
            $overallRanks[$s->id] = $rank;
        }

        foreach ($students as $student) {
            // Overall Position
            $student->overall_position = $overallRanks[$student->id] ?? null;

            // --- Category-wise Position ---
            $groups = [
                '1-7'   => [1, 7],
                '8-13'  => [8, 13],
                '14-29' => [14, 30]
            ];

            $studentCategory = null;
            foreach ($groups as $key => [$min, $max]) {
                if ($student->kul_para >= $min && $student->kul_para <= $max) {
                    $studentCategory = $key;
                    break;
                }
            }

            $student->category_position = null;
            if ($studentCategory) {
                $categoryStudents = $rankableStudents->filter(function ($s) use ($studentCategory, $groups) {
                    [$min, $max] = $groups[$studentCategory];
                    return $s->kul_para >= $min && $s->kul_para <= $max;
                });

                $sortedCategory = $categoryStudents->sortByDesc(fn($s) => $s->exam->total)->values();

                $catRanks = [];
                $rank = 0;
                $lastScore = null;
                foreach ($sortedCategory as $s) {
                    if ($s->exam->total !== $lastScore) {
                        $rank++;
                        $lastScore = $s->exam->total;
                    }
                    $catRanks[$s->id] = $rank;
                }

                $student->category_position = $catRanks[$student->id] ?? null;
            }

            // --- Class-wise Position ---
            $classStudents = $rankableStudents->filter(fn($s) => $s->class === $student->class);
            $sortedClass = $classStudents->sortByDesc(fn($s) => $s->exam->total)->values();

            $classRanks = [];
            $rank = 0;
            $lastScore = null;
            foreach ($sortedClass as $s) {
                if ($s->exam->total !== $lastScore) {
                    $rank++;
                    $lastScore = $s->exam->total;
                }
                $classRanks[$s->id] = $rank;
            }

            $student->class_position = $classRanks[$student->id] ?? null;

            // --- Grade Calculation (percentage ke itbar se) ---
            $percentage = $student->exam?->percentage ?? 0;
            if ($percentage >= 90) {
                $grade = "A+";
            } elseif ($percentage >= 85) {
                $grade = "A";
            } elseif ($percentage >= 80) {
                $grade = "B+";
            } elseif ($percentage >= 75) {
                $grade = "B";
            } elseif ($percentage >= 70) {
                $grade = "C+";
            } elseif ($percentage >= 65) {
                $grade = "C";
            } elseif ($percentage >= 60) {
                $grade = "D";
            } else {
                $grade = "Fail";
            }
            $student->grade = $grade;
        }

               $allClasses = Student::select('class')->distinct()->pluck('class');
        return view('students.allstudent', compact('students','allClasses'));
}

    public function create()
    {
        $students = Student::all();
        return view('exams.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'      => 'required|exists:students,id',

            // Marks validation
            'zabt'            => 'required|integer|min:0|max:50',
            'tajweed'         => 'required|integer|min:0|max:20',
            'lehja'           => 'required|integer|min:0|max:10',
            'tarbiti_nisab'   => 'required|integer|min:0|max:20',
            'guzashta_jaiza'  => 'required|integer|min:0|max:10',
            'hazri'           => 'required|integer|min:0|max:10',
            'tarjuma'         => 'required|integer|min:0|max:30',
        ]);

        // ✅ Total calculate (out of 150)
        $total =
            $request->zabt +
            $request->tajweed +
            $request->lehja +
            $request->tarbiti_nisab +
            $request->guzashta_jaiza +
            $request->hazri +
            $request->tarjuma;
       
        // ✅ Percentage calculate
        $fullMarks = 150;
        $percentage = round(($total / $fullMarks) * 100, 2);

        // ✅ Update or Create Exam record
        $exam = Exam::updateOrCreate(
            ['student_id' => $request->student_id],
            [
                'zabt'            => $request->zabt,
                'tajweed_lehja'   => $request->tajweed,
                'lehja'           => $request->lehja,
                'tarbiti_nisab'   => $request->tarbiti_nisab,
                'guzashta_jaiza'  => $request->guzashta_jaiza,
                'hazri'           => $request->hazri,
                'tarjuma'         => $request->tarjuma,
                'total'           => $total,
                'percentage'      => $percentage,
            ]
        );

        return redirect()
            ->route('exams.index')
            ->with('success', 'امتحانی ریکارڈ کامیابی سے محفوظ ہو گیا ✅');
    }



    public function edit(Exam $exam)
    {
        $students = Student::all();
        return view('exams.edit', compact('exam', 'students'));
    }
    public function show(Request $request)
    {
         
        $query = Student::query();

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('miqdar')) {
            $query->where('miqdar_e_khundgi', $request->miqdar);
        }
        $para = (int) $request->para ?? (int)30;
        if ($para > 1 && $para <= 7) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND ?', [$para]);
        }
        elseif ( $para >= 7 && $para <= 15) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 8 AND ?', [$para]);
        } elseif ($para >= 15 && $para <= 25) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 16 AND ?', [$para]);
        } elseif ($para >= 25 && $para <= 30) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 26 AND ?', [$para]);
        } else {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND 30');
        }
        $query->orderBy('id');
        // dd( $query->count());
        $students = $query->get();
        $allClasses = Student::select('class')->distinct()->pluck('class');

        return view('exams.sheet', compact('students', 'allClasses'));
    }

    public function update(Request $request, Exam $exam)
    {
      
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'zabt' => 'required|integer',
            'tajweed_lehja' => 'required|integer',
            'tarbiti_nisab' => 'required|integer',
            'guzashta_jaiza' => 'required|integer',
            'hazri' => 'required|integer',
            'tarjuma' => 'required|integer',
        ]);

        // total calculate
        $total = $request->zabt 
                + $request->tajweed_lehja 
                + $request->tarbiti_nisab 
                + $request->guzashta_jaiza 
                + $request->hazri 
                + $request->tarjuma;

        // update or create exam for this student
        Exam::updateOrCreate(
            ['student_id' => $request->student_id], // search by student_id
            [
                'zabt' => $request->zabt,
                'tajweed_lehja' => $request->tajweed_lehja,
                'tarbiti_nisab' => $request->tarbiti_nisab,
                'guzashta_jaiza' => $request->guzashta_jaiza,
                'hazri' => $request->hazri,
                'tarjuma' => $request->tarjuma,
                'total' => $total,
            ]
        );
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
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 16 AND ?', [$para]);
        } elseif ($para >= 15 && $para <= 25) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 16 AND ?', [$para]);
        } elseif ($para >= 25 && $para <= 30) {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 26 AND ?', [$para]);
        } else {
            $query->whereRaw('CAST(kul_para AS UNSIGNED) BETWEEN 1 AND 30');
        }
        $query->orderBy('id');
       $allClasses = Student::select('class')->distinct()->pluck('class');
        return view('exams.index', compact('students','allClasses'));
    }


    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Exam deleted');
    }
   
    public function updateField(Request $request)
    {
        $request->validate([
            'pk' => 'required|integer|exists:exams,id',
            'name' => 'required|string',
            'value' => 'required|string|max:255'
        ]);

        $exam = Exam::findOrFail($request->pk);
        $field = $request->name;
        $value = $request->value;

        // Make sure field is allowed to be updated
        $allowedFields = ['zabt', 'tajweed_lehja', 'tarbiti_nisab', 'guzashta_jaiza', 'hazri', 'tarjuma'];
        if (!in_array($field, $allowedFields)) {
            return response()->json(['error' => 'Invalid field'], 400);
        }

        $exam->$field = $value;

        // Update total if necessary (example logic)
        $exam->total = $exam->zabt + $exam->tajweed_lehja + $exam->tarbiti_nisab + $exam->guzashta_jaiza + $exam->hazri + $exam->tarjuma;

        $exam->save();

        return response()->json(['success' => true]);
    }

    public function examSheet(Request $request)
    {
      
        $students =Student::all();
        dd($students);
        exit;
        return view('exams.sheet', compact(''));
    }

      public function classResult(Request $request)
{
    $query = Student::query()->with('exam');

    // 👉 Filter lagana
    if ($request->filled('class')) {
        $query->where('class', $request->class);
    }

    if ($request->filled('para')) {
        $query->where('miqdar_e_khundgi', $request->para);
    }

    $students = $query->orderBy('id')->get();

    // --- Class-wise summary calculations ---
    $grouped = $students->groupBy('class');
    $classSummaries = [];

    foreach ($grouped as $class => $classStudents) {
        $totalZabt = $classStudents->sum(fn($s) => $s->exam?->zabt ?? 0);
        $totalTajweed = $classStudents->sum(fn($s) => $s->exam?->tajweed_lehja ?? 0);
        $totalTarbiyati = $classStudents->sum(fn($s) => $s->exam?->tarbiti_nisab ?? 0);

        $count = $classStudents->count();

        // ✅ % calculate karna (each subject ke apne total marks se)
        $avgZabt = $count ? ($totalZabt / ($count * 60)) * 100 : 0;
        $avgTajweed = $count ? ($totalTajweed / ($count * 20)) * 100 : 0;
        $avgTarbiyati = $count ? ($totalTarbiyati / ($count * 20)) * 100 : 0;

        // ✅ overall = total marks / total possible * 100
        $overall = $count ? (
            ($totalZabt + $totalTajweed + $totalTarbiyati) / ($count * 100)
        ) * 100 : 0;

        $classSummaries[$class] = [
            'avgZabt' => round($avgZabt, 2),
            'avgTajweed' => round($avgTajweed, 2),
            'avgTarbiyati' => round($avgTarbiyati, 2),
            'overall' => round($overall, 2),
        ];

        // ✅ پوزیشنز assign کرنا
        $sorted = $classStudents->sortByDesc(function ($s) {
            return ($s->exam?->zabt ?? 0) 
                 + ($s->exam?->tajweed_lehja ?? 0) 
                 + ($s->exam?->tarbiti_nisab ?? 0);
        })->values();

        $rank = 1;
        foreach ($sorted as $s) {
            $s->position = $rank;
            $rank++;
        }
    }

    // 👉 Sab available classes dropdown ke liye
    $allClasses = Student::select('class')->distinct()->pluck('class');

    return view('exams.classResult', [
        'students' => $students,
        'allClasses' => $allClasses,
        'classSummaries' => $classSummaries,
        'selectedClass' => $request->class,
    ]);
}



}