<!doctype html>
<html lang="id">

<head>
    {{-- Meta --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Icon --}}
    <link rel="icon" href="/logo.png" type="image/x-icon" width="64" height="64"/>

    {{-- Judul --}}
    <title>Aplikasi Cashflow - Auth</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0, 255, 135, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 255, 135, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(95, 39, 205, 0.1) 0%, transparent 50%);
            animation: pulse 10s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }

        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        .card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--neon-green), #5f27cd, var(--neon-green));
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .card-body {
            padding: 3rem 2.5rem;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section img {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 20px var(--neon-green-glow));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .logo-section h2 {
            color: var(--neon-green);
            font-size: 2rem;
            text-shadow: 0 0 30px var(--neon-green-glow);
            margin-bottom: 0.5rem;
        }

        .logo-section p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: var(--dark-bg);
            border: 2px solid var(--dark-border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            background: var(--dark-card);
            border-color: var(--neon-green);
            box-shadow: 0 0 0 0.3rem rgba(0, 255, 135, 0.15);
            color: var(--text-primary);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        .text-danger {
            color: #ff4757 !important;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--neon-green) 0%, var(--neon-green-dark) 100%);
            color: var(--dark-bg);
            box-shadow: 0 8px 30px rgba(0, 255, 135, 0.3);
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 255, 135, 0.5);
            background: var(--neon-green);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        hr {
            border-color: var(--dark-border);
            opacity: 1;
            margin: 2rem 0;
        }

        .text-center a {
            color: var(--neon-green);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .text-center a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--neon-green);
            transition: width 0.3s ease;
        }

        .text-center a:hover {
            text-shadow: 0 0 20px var(--neon-green-glow);
        }

        .text-center a:hover::after {
            width: 100%;
        }

        .text-center p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Loading Animation */
        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .logo-section h2 {
                font-size: 1.75rem;
            }

            body {
                padding: 1rem;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
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
    </style>
</head>

<body>
    <div class="auth-container">
        @yield('content')
    </div>

    {{-- Scripts --}}
    @livewireScripts
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>