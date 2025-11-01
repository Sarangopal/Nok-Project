@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Patrons Message | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>

<!--==============================
    Breadcrumb
==============================-->
<div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Patron’s Message</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Patron’s Message</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--==============================
    Team Details Area
==============================-->
<section class="space-top space-extra-bottom">
    <div class="container">
        <div class="row gx-60 gy-2">
            <div class="col-lg-5 mb-30 wow fadeInUp" data-wow-delay="0.2s">
                <img src="{{ asset('nokw/assets/img/committeee/Mr-Roy-yohannan.jpg') }}" 
                     style="width:490px; height:550px; object-fit:cover;" 
                     alt="MR. ROY YOHANNAN">
            </div>
            <div class="col-lg-7 col-xl-7 align-self-center mb-30">
                <div class="team-about">
                    <h3 class="team-name h4">Patron’s Message</h3>
                    <p style="margin-bottom: 10px;">Dear Friends and Members,</p>
                    <p class="team-text">
                        It is a privilege and an honor to serve as the Patron of Nightingales of Kuwait (NOK). 
                        Our organization was founded with a vision to unite Indian nurses in Kuwait, foster professional excellence, 
                        and create a supportive network that uplifts every member. Over the years, I have witnessed how NOK has grown 
                        into a vibrant community that embodies compassion, dedication, and service to society.
                    </p>
                    <p>
                        Let us continue this journey with unity and purpose, keeping service and humanity at the heart of everything we do.
                    </p>
                    <h6>Warm Wishes,</h6>
                    <h4 class="team-name h5">MR. ROY YOHANNAN</h4>
                    <span class="team-degi">Patron</span>
                    <span class="team-degi" style="margin-top: -20px;">Nightingales of Kuwait (NOK)</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ===== Custom Footer Background ===== */
.custom-footer-bg {
  background-image: url("{{ asset('nokw/nokw/assets/img/fooot.jpg') }}");
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
