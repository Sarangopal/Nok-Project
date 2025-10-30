@extends('layouts.frontend')

@section('title', 'Reset Password')

@section('content')
<section class="image-overlay-dark" data-bg-src="{{ asset('nokw/assets/img/hero/banok.jpg') }}">
    <div class="container" style="padding: 60px 0;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-white mb-4">Reset Your Password</h2>
                
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('member.password.email') }}" class="form-style1">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-white mb-2">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your registered email" value="{{ old('email') }}" required autofocus>
                        <small class="text-white-50">We'll send you a password reset link</small>
                    </div>
                    <button type="submit" class="vs-btn style5">Send Reset Link</button>
                    <a href="/member/panel/login" class="vs-btn style4 ms-2">Back to Login</a>
                </form>
            </div>
        </div>
    </div>
    <style>
        .form-control {
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</section>
@endsection

