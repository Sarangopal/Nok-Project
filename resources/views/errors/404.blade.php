@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'AARAVAM | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>
<!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">404 Page</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>404 Page</li>
                </ul>
            </div>
        </div>
    </div>
</div>


    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-1 fw-bold">404</h1>
                    <h2 class="mb-4">Page   Not Found</h2>              
                    <p class="mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
                </div>  

            </div>
        </div>          
    </section>
    
    
<!--==============================
    Footer Area
==============================-->
<style>
    /* ===== Custom Footer Background ===== */
    .custom-footer-bg {
        background-image: url("{{ asset('nokw/assets/img/fooot.jpg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
    }

    /* Optional: Improve mobile visibility */
    @media (max-width: 768px) {
        .custom-footer-bg {
            background-position: top center;
            background-size: cover;
        }
    }
</style>



@endsection
