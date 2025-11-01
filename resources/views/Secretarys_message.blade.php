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
        Breadcumb
    ==============================-->
    <div class="breadcumb-wrapper"
         style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Secretary’s Message</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Secretary’s Message</li>
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
                    <img src="{{ asset('nokw/assets/img/committeee/Mrs-Teena-susan-Thankachan.jpg') }}"
                         alt="MRS. TEENA SUSAN THANKACHAN"
                         style="width:490px; height:550px; object-fit:cover;">
                </div>

                <div class="col-lg-7 col-xl-7 align-self-center mb-30">
                    <div class="team-about">
                        <h3 class="team-name h4">Secretary’s Message</h3>

                        <p style="margin-bottom: 10px;">Dear Members and Well-Wishers,</p>

                        <p class="team-text">
                            It gives me immense pride and joy to serve as the Secretary of Nightingales of Kuwait (NOK).
                            Our organization stands as a beacon of unity, professionalism, and compassion for Indian nurses
                            across Kuwait. We are committed to supporting our members by providing opportunities for skill
                            development, continuing education, and professional networking while also reaching out to the
                            community through meaningful initiatives.
                        </p>

                        <p>
                            I invite all our members to actively engage in our programs and events, share your suggestions,
                            and help us build a stronger, more impactful association for the future.
                        </p>

                        <h6>Warm Regards,</h6>
                        <h4 class="team-name h5">MRS. TEENA SUSAN THANKACHAN</h4>
                        <span class="team-degi">General Secretary</span><br>
                        <span class="team-degi" style="margin-top:-40px;">Nightingales of Kuwait (NOK)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
