@extends('layouts.frontend')

@section('title', 'Member Login')

@section('content')
<section class="image-overlay-dark" data-bg-src="{{ asset('nokw/assets//img/hero/banok.jpg') }}">
    <div class="container" style="padding: 60px 0;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-white mb-4">Member Login</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('member.login.perform') }}" class="form-style1">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email (Optional)" value="{{ old('email') }}" autofocus>
                        <small class="text-white-50">You can login with just Civil ID and Password</small>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="civil_id" class="form-control" placeholder="Civil ID *" value="{{ old('civil_id') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password *" required>
                    </div>
                    <div class="form-group mb-3">
                        <label style="color:#fff;"><input type="checkbox" name="remember"> Remember me</label>
                    </div>
                    <button type="submit" class="vs-btn style5">Login</button>
                </form>
            </div>
        </div>
    </div>
    <style>.form-control{padding:12px;border-radius:6px;border:1px solid #ddd;}</style>
</section>
@endsection


