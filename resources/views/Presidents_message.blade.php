@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'President’s Message | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>
  <!--==============================
        Breadcumb
    ==============================-->
    <div class="breadcumb-wrapper"
         style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">President’s Message</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>President’s Message</li>
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
                    <img src="{{ asset('nokw/assets/img/committeee/Mr-Sijumon-Thomas.JPG') }}"
                         alt="MR. SIJUMON THOMAS"
                         style="width:490px; height:550px; object-fit:cover;">
                </div>
                <div class="col-lg-7 col-xl-7 align-self-center mb-30">
                    <div class="team-about">
                        <h3 class="team-name h4">President’s Message</h3>

                        <p style="margin-bottom: 10px;">Dear Members,</p>

                        <p class="team-text">
                            It is with immense pride and gratitude that I extend my heartfelt greetings to all members
                            and well-wishers of the Nightingales of Kuwait (NOK). Since its inception, our association
                            has been a symbol of unity, service, and professional growth for Indian nurses across Kuwait.
                            What began as a small initiative to connect and support one another has now grown into a
                            strong and respected platform, empowering our members to thrive both personally and
                            professionally.
                        </p>

                        <p>
                            Let us move forward with renewed energy and shared commitment to make NOK an even stronger
                            voice for nurses in Kuwait.
                        </p>

                        <h6>Warm Regards,</h6>
                        <h4 class="team-name h5">MR. SIJUMON THOMAS</h4>
                        <span class="team-degi">President</span><br>
                        <span class="team-degi" style="margin-top: -40px;">Nightingales of Kuwait (NOK)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>





@endsection
