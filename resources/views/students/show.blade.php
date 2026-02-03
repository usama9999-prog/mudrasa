<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>رزلٹ کارڈ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap');

    body {
        font-family: 'Noto Nastaliq Urdu', serif;
        background: #f0f5f2;
    }

    .page {
        max-width: 100%;
        margin: auto;
        padding: 20px;
    }

    .print-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0px 5px 20px rgba(0,0,0,0.12);
        padding: 25px;
        position: relative;
        border: 3px solid #198754;
        width: 48%;
        box-sizing: border-box;
        page-break-inside: avoid;
    }

    .watermark {
        position: absolute;
        top: 50%;
        right: 50%;
        transform: translate(50%, -50%);
        opacity: 0.05;
        width: 25%;
        max-width: 150px;
        z-index: 0;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #198754;
        padding-bottom: 10px;
    }

    .header h2 {
        color: #198754;
        font-weight: bold;
        font-size: 22px;
    }

    .header img.jamia-banner {
        width: 180px;
        max-width: 180px;
        height: auto;
    }

    .bismillah {
        font-size: 18px;
        color: #c59d23;
        margin-bottom: 10px;
    }

    .student-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 15px;
        z-index: 2;
    }

    .student-info span {
        background: #f8fcf9;
        padding: 6px 10px;
        border-radius: 6px;
        display: block;
        border: 1px solid #d3e6d8;
    }

    table.result-table {
        width: 100%;
        border-collapse: collapse;
    }

    .result-table th, .result-table td {
        border: 1px solid #dee2e6;
        padding: 6px;
        text-align: center;
        font-size: 14px;
    }

    .result-table th {
        background: #198754;
        color: #fff;
    }

    .total-section td {
        font-weight: bold;
        background: #fdf6e3;
    }

    .signatures {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-top: 15px;
    }

    @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; margin: 0; }
        .page { padding: 0; }
        .card {
            width: 48% !important;
            border: 3px solid #198754 !important;
            box-shadow: none !important;
        }
        .watermark {
            width: 25% !important;
            max-width: 150px !important;
            opacity: 0.05 !important;
        }
        .header img.jamia-banner {
            width: 180px !important;
            max-width: 180px !important;
        }
           .signatures {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-top: 15px;
    }
    }
  </style>
</head>
<body>
<div class="page">

  <!-- پرنٹ بٹن -->
  <div class="text-center mb-3 no-print">
      <button class="btn btn-success" onclick="printDiv('printArea')">
          <i class="bi bi-printer"></i> پرنٹ کریں
      </button>
  </div>

  <!-- Cards Area -->
  <div class="print-container" id="printArea">

      <!-- ہر بچے کا رزلٹ card -->
      <div class="card">
          <img src="image/logo.jpeg" alt="واٹرمارک" class="watermark">

          <div class="header">
              <p class="bismillah">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِیْمِ</p>
              <img src="{{asset('image/jamia3.png')}}" alt="جامعہ محمدیہ دارالقرآن" class="jamia-banner">
              <h2 class="mt-2">۵ ماہی امتحان</h2>
          </div>

          <div class="student-info">
              <span>نام طالب علم: {{$student->name}}</span>
              <span>کلاس: {{$student->class}}</span>
              <span>کل پارہ: {{$student->kul_para}}</span>
              <span>مقدارِ خواندگی: {{$student->miqdar_e_khundgi}}</span>
          </div>

          <div class="row g-3 mb-3">
              <div class="col-6">
                  <table class="table result-table text-center">
                      <thead><tr><th>مضمون</th><th>نمبر</th></tr></thead>
                      <tbody>
                          <tr><td>ضبط (60)</td><td>{{ $student->exam?->zabt }}</td></tr>
                          <tr><td>تجوید و لہجہ (20)</td><td>{{ $student->exam?->tajweed_lehja }}</td></tr>
                          <tr><td>تربیتی نصاب (20)</td><td>{{ $student->exam?->tarbiti_nisab }}</td></tr>
                      </tbody>
                  </table>
              </div>

              <div class="col-6">
                  <table class="table result-table text-center">
                      <thead><tr><th>مضمون</th><th>نمبر</th></tr></thead>
                      <tbody>
                          <tr><td>ترجمہ (30)</td><td>{{ $student->exam?->tarjuma }}</td></tr>
                          <tr><td>گزشتہ جائزہ (10)</td><td>{{ $student->exam?->guzashta_jaiza }}</td></tr>
                          <tr><td>حاضری (10)</td><td>{{ $student->exam?->hazri }}</td></tr>
                      </tbody>
                  </table>

                  <table class="table result-table text-center mt-2 total-section">
                      <tr><td>کل (150)</td><td>{{ $student->exam?->total }}</td></tr>
                      <tr><td>فیصد</td><td>{{ $student->exam?->percentage }}%</td></tr>
                      <tr><td>پوزیشن</td><td>____________</td></tr>
                  </table>
              </div>
          </div>

            <div class="signatures">
                <span>معلمِ : ____________</span>
                <span>ناظمِ : ____________</span>
                <span>سرپرستِ : ____________</span>
            </div>

      </div>

  </div>
</div>

<script>
    function printDiv(divId) {
        var content = document.getElementById(divId).innerHTML;
        var styles = document.querySelector('style').outerHTML;

        var myWindow = window.open('', '', 'width=1000,height=800');
        myWindow.document.write(`
            <html>
                <head>
                    <title>پرنٹ</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    ${styles}
                </head>
                <body>${content}</body>
            </html>
        `);
        myWindow.document.close();
        myWindow.print();
    }
</script>
</body>
</html>
