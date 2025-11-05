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

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    {{-- ApexCharts CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">
    
    {{-- Custom CSS --}}
    <style>
        .card-stats {
            border-left: 4px solid;
        }
        .card-stats.income {
            border-left-color: #28a745;
        }
        .card-stats.expense {
            border-left-color: #dc3545;
        }
        .card-stats.balance {
            border-left-color: #007bff;
        }
        .badge-income {
            background-color: #28a745;
        }
        .badge-expense {
            background-color: #dc3545;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .receipt-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-light">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('app.finance') }}">
                <strong>💰 Catatan Keuangan</strong>
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
                    title: data.type === 'success' ? 'Berhasil!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
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
                width: 'auto'
            });
        }
    </script>
    
    @stack('scripts')
</body>

</html>