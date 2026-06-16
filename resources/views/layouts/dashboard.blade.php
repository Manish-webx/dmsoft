<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    @stack('styles')

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --brand-navy: #1B2A4A;
            --brand-navy-light: #263A5E;
            --brand-rose: #B76E79;
            --brand-rose-dark: #9E5563;
            --brand-bg: #FEFBFB;
            --text-dark: #333333;
            --text-light: #fdfdfd;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-bg);
            color: var(--text-dark);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .brand-heading {
            font-family: 'Playfair Display', serif;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--brand-navy);
            padding-top: 20px;
            color: var(--text-light);
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: #d1d5db;
            padding: 12px 20px;
            margin-bottom: 5px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: var(--brand-navy-light);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: var(--brand-navy-light);
            color: #fff;
            border-left: 4px solid var(--brand-rose);
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            color: var(--brand-rose);
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: #fff;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: all 0.3s ease;
        }

        .topbar {
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            padding: 15px 25px;
            margin-bottom: 30px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            background-color: #fff;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--brand-rose), var(--brand-rose-dark));
            border: none;
            color: #fff;
            border-radius: 20px;
            padding: 8px 20px;
            box-shadow: 0 4px 10px rgba(183, 110, 121, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, var(--brand-rose-dark), var(--brand-rose));
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(183, 110, 121, 0.4);
            color: #fff;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: var(--brand-navy);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 8px 20px;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            color: var(--brand-navy);
        }

        /* Modals */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: var(--brand-bg);
            border-bottom: 1px solid #eee;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--brand-navy);
        }

        /* Forms */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-rose);
            box-shadow: 0 0 0 0.25rem rgba(183, 110, 121, 0.25);
        }

        label {
            font-weight: 500;
            color: var(--brand-navy);
            margin-bottom: 5px;
        }

        /* Tables */
        .table {
            color: var(--text-dark);
        }

        .table thead th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: var(--brand-navy);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4 mt-2">
            <img src="https://www.dermatales.com/images/logo.png" alt="Dermatales Logo"
                style="width: 150px; height: auto; background-color: rgba(255, 255, 255, 0.9); padding: 10px; border-radius: 8px;">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
                    href="{{ route('appointments.index') }}">
                    <i class="fas fa-calendar-check"></i> Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                    href="{{ route('patients.index') }}">
                    <i class="fas fa-users"></i> Patients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}"
                    href="{{ route('prescriptions.index') }}">
                    <i class="fas fa-prescription-bottle-alt"></i> Prescriptions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}"
                    href="{{ route('medicines.index') }}">
                    <i class="fas fa-pills"></i> Medicines
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('problems.*') ? 'active' : '' }}"
                    href="{{ route('problems.index') }}">
                    <i class="fas fa-list-alt"></i> Problems
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('followups.*') ? 'active' : '' }}"
                    href="{{ route('followups.index') }}">
                    <i class="fas fa-clock"></i> Follow-ups
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('revenues.*') ? 'active' : '' }}"
                    href="{{ route('revenues.index') }}">
                    <i class="fas fa-file-invoice-dollar"></i> Billing
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h5 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h5>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name ?? 'User' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global Toastr Options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
        };

        // Flash Messages
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
    </script>

    @stack('scripts')
</body>

</html>