<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>حصہ داروں کی فہرست - PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3 style="text-align:center;">حصہ داروں کی مکمل فہرست</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>پتہ</th>
                <th>رسید نمبر</th>
                <th>موبائل</th>
                <th>شمولیت کی تاریخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shareholders as $index => $shareholder)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $shareholder->name }}</td>
                <td>{{ $shareholder->address ?? '-' }}</td>
                <td>{{ $shareholder->receipt_no }}</td>
                <td>{{ $shareholder->mobile ?? '-' }}</td>
                <td>{{ $shareholder->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
