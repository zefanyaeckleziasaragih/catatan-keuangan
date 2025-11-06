<!doctype html>
<html lang="id">

<head>
    {{-- Meta --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Icon --}}
    <link rel="icon" href="/logo.png" type="image/x-icon" />

    {{-- Judul --}}
    <title>Catatan Keuangan - @yield('title', 'Dashboard')</title>

    {{-- Google Fonts - Playfair Display & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    {{-- ApexCharts CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">
    
    {{-- Custom CSS --}}
    <style>
        :root {
            --neon-green: #00ff87;
            --neon-green-dark: #00cc6d;
            --neon-green-glow: rgba(0, 255, 135, 0.5);
            --dark-bg: #0a0e27;
            --dark-card: #151b35;
            --dark-border: #1e2847;
            --text-primary: #ffffff;
            --text-secondary: #8b92b0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .heading-font {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, #0a0e27 0%, #151b35 100%);
            border-bottom: 1px solid var(--dark-border);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--neon-green) !important;
            text-shadow: 0 0 20px var(--neon-green-glow);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            text-shadow: 0 0 30px var(--neon-green-glow);
            transform: scale(1.05);
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--neon-green);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--neon-green) !important;
        }

        .nav-link.active::after,
        .nav-link:hover::after {
            width: 80%;
        }

        .dropdown-menu {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .dropdown-item {
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--dark-bg);
            color: var(--neon-green);
        }

        /* Card Styling */
        .card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 48px rgba(0, 255, 135, 0.1);
        }

        .card-stats {
            border-left: 4px solid;
            position: relative;
            overflow: hidden;
        }

        .card-stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(0, 255, 135, 0.05) 100%);
            pointer-events: none;
        }

        .card-stats.income {
            border-left-color: var(--neon-green);
        }

        .card-stats.expense {
            border-left-color: #ff4757;
        }

        .card-stats.balance {
            border-left-color: #5f27cd;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Badge Styling */
        .badge-income {
            background: linear-gradient(135deg, var(--neon-green) 0%, var(--neon-green-dark) 100%);
            color: var(--dark-bg);
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .badge-expense {
            background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        /* Button Styling */
        .btn {
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--neon-green) 0%, var(--neon-green-dark) 100%);
            color: var(--dark-bg);
            box-shadow: 0 4px 20px rgba(0, 255, 135, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(0, 255, 135, 0.5);
            background: var(--neon-green);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffa502 0%, #ff7f00 100%);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(255, 165, 2, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(255, 71, 87, 0.4);
        }

        .btn-secondary {
            background: var(--dark-border);
            color: var(--text-secondary);
        }

        .btn-secondary:hover {
            background: var(--dark-bg);
            color: var(--text-primary);
        }

        /* Form Styling */
        .form-control,
        .form-select {
            background: var(--dark-bg);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: var(--dark-card);
            border-color: var(--neon-green);
            box-shadow: 0 0 0 0.2rem rgba(0, 255, 135, 0.25);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Table Styling */
        .table {
            color: var(--text-primary);
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table thead th {
            background: var(--dark-bg);
            color: var(--neon-green);
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            padding: 1rem;
        }

        .table tbody tr {
            background: rgba(21, 27, 53, 0.5);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(30, 40, 71, 0.8);
            transform: scale(1.01);
            box-shadow: 0 4px 20px rgba(0, 255, 135, 0.1);
        }

        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table tbody tr td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Modal Styling */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            border-bottom: 1px solid var(--dark-border);
            padding: 1.5rem;
        }

        .modal-title {
            color: var(--neon-green);
        }

        .modal-footer {
            border-top: 1px solid var(--dark-border);
            padding: 1.5rem;
        }

        .btn-close {
            filter: invert(1);
        }

        /* Receipt Preview */
        .receipt-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 10px;
            border: 2px solid var(--dark-border);
            transition: all 0.3s ease;
        }

        .receipt-preview:hover {
            transform: scale(1.05);
            border-color: var(--neon-green);
            box-shadow: 0 4px 20px rgba(0, 255, 135, 0.3);
        }

        /* Pagination */
        .pagination {
            margin-top: 1rem;
        }

        .page-link {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            color: var(--text-secondary);
            margin: 0 0.25rem;
            border-radius: 8px;
        }

        .page-link:hover {
            background: var(--dark-border);
            color: var(--neon-green);
            border-color: var(--neon-green);
        }

        .page-item.active .page-link {
            background: var(--neon-green);
            color: var(--dark-bg);
            border-color: var(--neon-green);
        }

        /* Text Colors */
        .text-success {
            color: var(--neon-green) !important;
        }

        .text-danger {
            color: #ff4757 !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        /* Spinner */
        .spinner-border {
            border-color: var(--neon-green);
            border-right-color: transparent;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--dark-border);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--neon-green);
        }

        /* Glow Effects */
        .glow-text {
            text-shadow: 0 0 20px var(--neon-green-glow);
        }

        /* Alert from SweetAlert2 */
        .swal2-popup {
            background: var(--dark-card) !important;
            border: 1px solid var(--dark-border);
        }

        .swal2-title {
            color: var(--text-primary) !important;
        }

        .swal2-html-container {
            color: var(--text-secondary) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            .table {
                font-size: 0.85rem;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('app.finance') }}">
                💰 <span class="heading-font">CASHFLOW</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('app.finance') ? 'active' : '' }}" 
                           href="{{ route('app.finance') }}">
                            Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('app.statistics') ? 'active' : '' }}" 
                           href="{{ route('app.statistics') }}">
                            Statistik
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" 
                           role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('auth.logout') }}">
                                    Keluar
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div class="container-fluid">
        @yield('content')
    </div>

    {{-- Scripts --}}
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- ApexCharts JS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @livewireScripts
    
    <script>
        document.addEventListener("livewire:initialized", () => {
            // Handle closeModal event
            Livewire.on("closeModal", (data) => {
                const modalElement = document.getElementById(data.id);
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });

            // Handle showModal event
            Livewire.on("showModal", (data) => {
                const modalElement = document.getElementById(data.id);
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });

            // Handle showAlert event
            Livewire.on("showAlert", (data) => {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Berhasil!' : 'Berhasil!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    background: '#151b35',
                    color: '#ffffff'
                });
            });
        });

        // Format currency input
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Preview image
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                    document.getElementById(previewId).style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // View full image in modal
        function viewImage(src) {
            Swal.fire({
                imageUrl: src,
                imageAlt: 'Receipt',
                showCloseButton: true,
                showConfirmButton: false,
                width: 'auto',
                background: '#151b35'
            });
        }
    </script>
    
    @stack('scripts')
</body>

</html>