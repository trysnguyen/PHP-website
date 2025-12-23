<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Library Management System - Student Project">
    <meta name="author" content="Nguyen Dinh Bao Tri">
    
    <title>@yield('title', 'Library Management System')</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        * {
            font-family: 'Roboto', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .main-content {
            flex: 1;
            padding-top: 80px; /* Space for fixed header */
            padding-bottom: 40px;
        }
        
        /* ========== HEADER STYLES ========== */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a2530 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            padding: 0;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 0;
        }
        
        .navbar-brand .brand-icon {
            color: var(--secondary-color);
            font-size: 2rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 12px 20px !important;
            margin: 0 5px;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link i {
            font-size: 1.1rem;
        }
        
        .user-info {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-left: 15px;
        }
        
        .logout-btn {
            background: rgba(231, 76, 60, 0.9);
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: var(--danger-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }
        
        /* ========== FOOTER STYLES ========== */
        .main-footer {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1a2530 100%);
            color: rgba(255, 255, 255, 0.85);
            padding: 40px 0 20px;
            margin-top: auto;
            border-top: 4px solid var(--secondary-color);
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .footer-logo i {
            color: var(--secondary-color);
            font-size: 2.5rem;
        }
        
        .footer-logo h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .footer-links {
            display: flex;
            gap: 30px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .footer-links a:hover {
            color: var(--secondary-color);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        
        .footer-bottom a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .footer-bottom a:hover {
            text-decoration: underline;
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .main-content {
                padding-top: 70px;
            }
            
            .nav-link {
                padding: 10px 15px !important;
                margin: 5px 0;
            }
            
            .user-info {
                margin: 10px 0;
                text-align: center;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 30px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-brand .brand-icon {
                font-size: 1.8rem;
            }
        }
        
        /* ========== CARD STYLES ========== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        /* ========== ALERT STYLES ========== */
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 4px solid var(--danger-color);
        }
        
        /* ========== BUTTON STYLES ========== */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #219653 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d68910 100%);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%);
        }
    </style>
    
    @stack('styles') <!-- For additional page-specific styles -->
</head>
<body>
    <!-- ========== HEADER ========== -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="/">
                    <i class="bi bi-book-half brand-icon"></i>
                    <span>LibraSys</span>
                </a>
                
                <!-- Mobile Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        @if(Session::get('admin_logged_in'))
                            <!-- ADMIN MENU -->
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('admin/dashboard')) active @endif" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('admin/orders')) active @endif" 
                                   href="{{ route('admin.orders') }}">
                                    <i class="bi bi-list-check"></i> Orders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('admin/statistics')) active @endif" 
                                   href="{{ route('admin.statistics') }}">
                                    <i class="bi bi-graph-up"></i> Statistics
                                </a>
                            </li>
                            
                            <li class="nav-item d-flex align-items-center">
                                <div class="user-info">
                                    <i class="bi bi-person-circle"></i>
                                    {{ Session::get('username') }} (Admin)
                                </div>
                            </li>
                            
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn logout-btn">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                            
                        @elseif(Session::get('student_logged_in'))
                            <!-- STUDENT MENU -->
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('student/dashboard')) active @endif" 
                                   href="{{ route('student.dashboard') }}">
                                    <i class="bi bi-house-door"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('student/history')) active @endif" 
                                   href="{{ route('student.history') }}">
                                    <i class="bi bi-clock-history"></i> History
                                </a>
                            </li>
                            
                            <li class="nav-item d-flex align-items-center">
                                <div class="user-info">
                                    <i class="bi bi-person-circle"></i>
                                    {{ Session::get('username') }} (Student)
                                </div>
                            </li>
                            
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn logout-btn">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                            
                        @else
                            <!-- PUBLIC MENU -->
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('login')) active @endif" 
                                   href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::is('register')) active @endif" 
                                   href="{{ route('register') }}">
                                    <i class="bi bi-person-plus"></i> Register
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Success!</h5>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Error!</h5>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- ========== FOOTER ========== -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <!-- Logo & Info -->
                <div class="footer-logo">
                    <i class="bi bi-book-half"></i>
                    <div>
                        <h4>Library Management System</h4>
                        <p class="mb-0">Efficient book tracking & borrowing system</p>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-links">
                    <a href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    @if(Session::get('admin_logged_in'))
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Admin Dashboard
                    </a>
                    @elseif(Session::get('student_logged_in'))
                    <a href="{{ route('student.dashboard') }}">
                        <i class="bi bi-house-door"></i> Student Dashboard
                    </a>
                    @endif
                    <a href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="footer-bottom">
                <p class="mb-0">
                    &copy; 2025 <strong>Library Management System</strong> 
                    | Student Project by <strong>Nguyen Dinh Bao Tri</strong>
                    | <i class="bi bi-code-slash"></i> Built with Laravel & Bootstrap
                </p>
                <p class="mt-2 mb-0">
                    <small>
                        <i class="bi bi-envelope"></i> Contact: trindb.24ai@vku.udn.vn |
                        <i class="bi bi-github"></i> GitHub: github.com/trysnguyen
                    </small>
                </p>
            </div>
        </div>
    </footer>

    <!-- ========== SCRIPTS ========== -->
    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Add active class to current page in navbar
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    
    <!-- Page-specific scripts -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>
