<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>کلاس وائز طلباء کی فہرست</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;
      direction: rtl;
    }
    .logo { width: 80px; height: auto; }
    .page-break { page-break-after: always; }
    table th, table td {
      text-align: center;
      vertical-align: middle;
      font-size: 14px;
    }
  </style>
</head>
<body onload="window.print()">

  @foreach($classes as $className => $students)
    <div class="text-center mb-4">
      <img src="{{ asset('image/logo.png') }}" class="logo mb-2" alt="لوگو">
      <div>
        <img src="{{ asset('image/jamia2.png') }}" alt="جامعہ" style="max-width: 250px;">
      </div>
      <h2 class="mt-3">{{ $class }}</h2>
    </div>

    <div class="container">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th style="width:5%">نمبر شمار</th>
            <th style="width:25%">نام طالب علم</th>
            <th style="width:25%">والد کا نام</th>
            <th style="width:20%">آمد وقت</th>
            <th style="width:25%">دستخط سرپرست</th>
          </tr>
        </thead>
        <tbody>
          @foreach($students as $index => $student)
            <tr>
              <td>{{ $index+1 }}</td>
              <td>{{ $student->name }}</td>
              <td>{{ $student->father_name }}</td>
              <td>{{ $student->amad_waqt ?? '-' }}</td>
              <td></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="page-break"></div>
  @endforeach

</body>
</html>
