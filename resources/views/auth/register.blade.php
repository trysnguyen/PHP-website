@extends('layouts.app')

@section('title', 'Register - Library Management')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header bg-success text-white text-center py-3">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus-fill me-2"></i>Student Registration
                </h4>
                <p class="mb-0 small">Create your library account</p>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="row">
                        <!-- Username -->
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>Username *
                            </label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   required
                                   placeholder="Choose username">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Must be unique</small>
                        </div>
                        
                        <!-- Student ID -->
                        <div class="col-md-6 mb-3">
                            <label for="StudentID" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Student ID *
                            </label>
                            <input type="text" 
                                   class="form-control @error('StudentID') is-invalid @enderror" 
                                   id="StudentID" 
                                   name="StudentID" 
                                   value="{{ old('StudentID') }}"
                                   required
                                   placeholder="Enter student ID">
                            @error('StudentID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="Studentname" class="form-label">
                            <i class="bi bi-person-vcard me-1"></i>Full Name *
                        </label>
                        <input type="text" 
                               class="form-control @error('Studentname') is-invalid @enderror" 
                               id="Studentname" 
                               name="Studentname" 
                               value="{{ old('Studentname') }}"
                               required
                               placeholder="Enter your full name">
                        @error('Studentname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Class -->
                    <div class="mb-3">
                        <label for="Class" class="form-label">
                            <i class="bi bi-mortarboard me-1"></i>Class *
                        </label>
                        <input type="text" 
                               class="form-control @error('Class') is-invalid @enderror" 
                               id="Class" 
                               name="Class" 
                               value="{{ old('Class') }}"
                               required
                               placeholder="e.g., IT001, CS101">
                        @error('Class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Password *
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
                        
                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Confirm Password *
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Confirm password">
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-check-fill me-2"></i>Register Account
                        </button>
                        
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Login
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="card-footer text-center text-muted">
                <small>Already have an account? <a href="{{ route('login') }}">Login here</a></small>
            </div>
        </div>
    </div>
</div>
@endsection
