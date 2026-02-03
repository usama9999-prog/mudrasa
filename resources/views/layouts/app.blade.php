<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        @yield('title', ' مدرسہ محمدیہ دارالقرآن ')
    </title>



    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />

    <!-- Urdu Font -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />

    <style>
        
        body {
            font-family: 'Noto Nastaliq Urdu', 'Segoe UI', sans-serif;
            background-color: #f9fafb;
            direction: rtl;
            text-align: right;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            width: 350px;
            height: 350px;
            background: url('/imageJPEG/logo.png') no-repeat center center;
            background-size: contain;
            opacity: 0.05;
            transform: translate(-50%, -50%);
            z-index: -1;
            pointer-events: none;
        }

        .navbar {
            background-color: #ffffffcc;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .navbar-brand {
            color: #0d6efd;
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .navbar-nav .nav-link {
            color: #212529;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #0d6efd;
            text-decoration: underline;
        }

        main.container {
            margin-top: 40px;
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(13, 110, 253, 0.12);
        }

        footer {
            text-align: center;
            padding: 15px 0;
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 50px;
            border-top: 1px solid #e9ecef;
        }

        table.dataTable {
            direction: rtl !important;
            width: 100% !important;
        }

        table.dataTable thead th {
            background-color: #0d6efd;
            color: white;
            font-weight: 700;
            text-align: center;
        }

        table.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            main.container {
                padding: 20px 25px;
                margin-top: 25px;
            }
            .navbar-brand {
                font-size: 1.3rem;
            }
        }
        @media print {
            .animal-header {
                text-align: center !important;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                مدرسہ محمدیہ دارالقرآن - قربانی 2025
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">ڈیش بورڈ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('animals.index') }}">جانور</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shareholders.index') }}">حصہ دار</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('students.index') }}">طالب علم</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('exams.index', ['exam' => 5]) }}">پانچواں امتحان</a></li>
  <li class="nav-item">
          <a class="nav-link" href="{{ route('students.classwise-list') }}">📑 طلباء فہرست</a>
        </li>
                  
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        &copy; 2025 مدرسہ محمدیہ دارالقرآن 
    </footer>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <!-- DataTable Init -->
    <script>
        $(document).ready(function () {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/Urdu.json'
                },
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print', 'colvis'
                ]
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
