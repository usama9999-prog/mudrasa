<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تمام جانوروں کی رپورٹ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: 'Noto Nastaliq Urdu', 'Segoe UI', Tahoma, sans-serif;
            direction: rtl;
            background: #fff;
        }

        .page {
            page-break-after: always;
            padding: 10mm;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    @foreach($animals as $ani)
        @include('animals.partials.animal_card', ['ani' => $ani])
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
