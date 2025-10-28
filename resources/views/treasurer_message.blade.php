@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Treasurer Message | Nightingales of Kuwait')

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
            <h1 class="breadcumb-title">Treasurer Message</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Treasurer Message</li>
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
                <img src="{{ asset('nokw/assets/img/committeee/Mrs-Treesa-Abraham.jpg') }}" 
                     style="width:490px; height:550px; object-fit:cover;" 
                     alt="MRS. TREESA ABRAHAM">
            </div>
            <div class="col-lg-7 col-xl-7 align-self-center mb-30">
                <div class="team-about">
                   <h3 class="team-name h4">Treasurer Message</h3>
                    <p style="margin-bottom: 10px;">Dear Friends and Valued Members,</p>
                    <p class="team-text">
                        It is with a great sense of duty and responsibility that I address you as the **Treasurer** of Nightingales of Kuwait (NOK). Our organization's commitment to uniting Indian nurses and fostering professional excellence is closely tied to the **fiscal health and transparent management** of our resources.
                        I am proud to report that NOK remains in a **strong and stable financial position**, thanks to your dedicated membership and the prudent management of all funds.
                    </p>
                    <p class="team-text">
                        My goal is to ensure that every membership fee, donation, and generated surplus is utilized effectively to **directly support** our educational programs, welfare initiatives, and community outreach. We are committed to maintaining **complete transparency** so you can be confident that our financial practices directly strengthen our mission.
                    </p>
                    <p>
                        Let us continue to work together, securing the financial foundations that enable NOK to uplift every member and serve our community with compassion and dedication.
                    </p>
                    <h6>Warm Wishes,</h6>
                    <h4 class="team-name h5">MRS. TREESA ABRAHAM</h4>
                    <span class="team-degi">Treasurer</span>
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
