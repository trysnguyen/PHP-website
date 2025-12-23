<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Library Management System - Student Project">
    
    <title>@yield('title', 'Library Management System')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        /* ========== CẤU HÌNH CHUNG ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            padding-top: 80px; /* KHOẢNG CÁCH QUAN TRỌNG: Giữ chỗ cho fixed header */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* ========== HEADER VỚI KHOẢNG CÁCH ========== */
        .main-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 80px; /* Chiều cao cố định */
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .logo-icon {
            color: #60a5fa;
            font-size: 2rem;
            transition: transform 0.3s ease;
        }
        
        .logo:hover .logo-icon {
            transform: rotate(15deg);
        }
        
        .logo-text {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        /* Navigation */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 5px;
            height: 100%;
        }
        
        .nav-item {
            list-style: none;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            height: 48px;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-left: 10px;
            color: white;
            font-size: 0.9rem;
        }
        
        /* Logout Button */
        .logout-btn {
            background: rgba(220, 38, 38, 0.9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        /* ========== MAIN CONTENT - KHOẢNG CÁCH QUAN TRỌNG ========== */
        .main-content {
            flex: 1;
            padding: 40px 0; /* Khoảng cách trên dưới nội dung */
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        /* Container spacing */
        .content-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        /* ========== FOOTER ========== */
        .main-footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #cbd5e1;
            padding: 40px 0 20px;
            margin-top: 60px; /* Khoảng cách từ nội dung đến footer */
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .footer-logo i {
            color: #60a5fa;
            font-size: 2.5rem;
        }
        
        .footer-logo h4 {
            color: white;
            margin: 0;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            body {
                padding-top: 70px;
            }
            
            .main-header {
                height: 70px;
            }
            
            .nav-menu {
                gap: 3px;
            }
            
            .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            
            .main-content {
                padding: 30px 15px;
            }
            
            .content-container {
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .main-header {
                height: auto;
                padding: 10px 0;
            }
            
            .header-container {
                flex-direction: column;
                gap: 15px;
                padding: 10px 15px;
            }
            
            .nav-menu {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .user-info, .logout-btn {
                margin: 5px;
            }
            
            body {
                padding-top: 120px; /* Tăng khoảng cách cho header cao hơn */
            }
            
            .main-content {
                padding-top: 20px;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
        }
        
        /* ========== ALERTS ========== */
        .alert-container {
            margin-bottom: 25px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        /* ========== CARD STYLES ========== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
            font-weight: 600;
        }
        
        /* ========== TABLE STYLES ========== */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- ========== HEADER ========== -->
    <header class="main-header">
        <div class="header-container">
            <!-- Logo -->
            <a href="/" class="logo">
                <i class="bi bi-book-half logo-icon"></i>
                <span class="logo-text">Library System</span>
            </a>
            
            <!-- Navigation -->
            <ul class="nav-menu">
                @if(Session::get('admin_logged_in'))
                    <!-- ADMIN -->
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
                    
                    <div class="user-info">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ Session::get('username') }}</span>
                    </div>
                    
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                    
                @elseif(Session::get('student_logged_in'))
                    <!-- STUDENT -->
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
                    
                    <div class="user-info">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ Session::get('username') }}</span>
                    </div>
                    
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                    
                @else
                    <!-- PUBLIC -->
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
    </header>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <!-- Flash Messages -->
        <div class="alert-container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
        </div>
        
        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- ========== FOOTER ========== -->
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="bi bi-book-half"></i>
                    <div>
                        <h4>Library Management System</h4>
                        <p>Efficient book tracking system</p>
                    </div>
                </div>
                
                <div class="footer-info">
                    <p><i class="bi bi-code-slash"></i> Built with Laravel & Bootstrap</p>
                    <p><i class="bi bi-person-workspace"></i> Student Project</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="mb-0">
                    &copy; 2025 <strong>Library Management System</strong> | 
                    Student Project by <strong>Nguyen Dinh Bao Tri</strong>
                </p>
            </div>
        </div>
    </footer>

    <!-- ========== SCRIPTS ========== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Highlight active menu
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>
