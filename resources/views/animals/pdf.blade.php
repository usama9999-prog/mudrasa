<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Animals List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: ltr;
            text-align: left;
            font-size: 14px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px 10px;
        }
        th {
            background-color: #f0f0f0;
        }
        h2 {
            text-align: center;
            margin-top: 0;
        }
        .total-row {
            font-weight: bold;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

    <h2>Animals List</h2>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Animal Number</th>
                <th>Purchase Price </th>
                <th>Transport expense + Rent </th>
                <th>Fodder Expense</th>
                <th>Others Expense</th>
                <th>Total </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($animals as $index => $animal)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $animal->animal_no }}</td>
                <td>{{ number_format($animal->purchase_price, 2) }}</td>
                <td>{{ number_format(($animal->writing_cost ?? 0) + ($animal->transportation_cost ?? 0), 2) }}</td>
                <td>{{ number_format($animal->fodder_cost ?? 0, 2) }}</td>
                <td>{{ number_format($animal->miscellaneous_cost ?? 0, 2) }}</td>
                <td>{{ number_format(
                    ($animal->purchase_price ?? 0)
                    + ($animal->writing_cost ?? 0)
                    + ($animal->transportation_cost ?? 0)
                    + ($animal->fodder_cost ?? 0)
                    + ($animal->miscellaneous_cost ?? 0), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
