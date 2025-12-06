
@extends('layouts.app')

@section('title', 'Login - Library Management')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">
                    <i class="bi bi-door-open-fill me-2"></i>Login
                </h4>
                <p class="mb-0 small">Library Management System</p>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="bi bi-person-fill me-1"></i>Username
                        </label>
                        <input type="text" 
                               class="form-control @error('username') is-invalid @enderror" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}"
                               required 
                               autofocus
                               placeholder="Enter username">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>Password
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Enter password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </div>
                    
                    @error('login')
                        <div class="alert alert-danger text-center py-2">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="mb-2">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-1"></i>Register Now
                    </a>
                </div>
                
                <div class="mt-4 text-center text-muted small">
                    <p class="mb-2">
                        <strong>Test Accounts:</strong>
                    </p>
                    <div class="bg-light p-2 rounded">
                        <p class="mb-1"><strong>Admin:</strong> admin / admin</p>
                        <p class="mb-0"><strong>Student:</strong> Register new account</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection