@extends('layouts.frontend')

@section('title', 'Set New Password')

@section('content')
<section class="image-overlay-dark" data-bg-src="{{ asset('nokw/assets/img/hero/banok.jpg') }}">
    <div class="container" style="padding: 60px 0;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-white mb-4">Set New Password</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('member.password.update') }}" class="form-style1">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label class="text-white mb-2">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-white mb-2">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password (min 8 characters)" required autofocus>
                        <small class="text-white-50">Minimum 8 characters</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-white mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="vs-btn style5">Reset Password</button>
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
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</section>
@endsection

