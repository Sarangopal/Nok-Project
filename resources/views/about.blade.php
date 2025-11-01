@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'About | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>    <!--============================== Breadcumb ============================== -->
    <div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">About Us</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>About Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-5 col-xxl-auto mb-30 pb-20 pb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                    <img src="{{ asset('nokw/assets/img/hero/banok1.jpg') }}"
                         style="width: 550px; height: 530px; object-fit: cover; border-radius: 8px;"
                         alt="Skill image">
                </div>

                <div class="col-lg-7 col-xxl-6 me-xl-auto">
                    <span class="sec-subtitle"><i class="fas fa-bring-forward"></i>Who we are</span>
                    <h2 class="sec-title h1">Together We Care, Together We Grow</h2>
                    <p class="mb-4 pb-1">
                        Nightingales of Kuwait (NOK) is a non-profitable, non-political organization formed by Indian nurses officially registered under the Indian Embassy in Kuwait (Reg No: INDEMB/KWT/ASSN/401). Founded with a vision to unite, empower, and support the nursing community, NOK stands as a beacon of solidarity and service. We are committed to fostering professional growth, organizing health initiatives, and providing a platform for cultural and community engagement — all while adhering to the rules and regulations of the Kuwait Ministry.
                        <br>
                        At NOK, we believe in the power of togetherness and the spirit of service. Through training programs, health awareness camps, and patient support groups, we actively contribute to both professional development and public welfare. In times of crisis, whether personal or global, we stand beside our members, offering care, guidance, and hope. Guided by compassion, integrity, and inclusivity, NOK continues to grow as a trusted support system and a unifying voice for Indian nurses across Kuwait.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!--============================== About Us ==============================-->
    <div class="bg-smoke">
        <div class="container space-top space-extra-bottom">
            <div class="row">
                
                <div class="col-lg-6">
                    <div class="service-style2">
                        <div class="service-img" style="padding: 20px;">
                            <span class="service-number">01</span>
                            <img src="{{ asset('nokw/assets/img/service/1.png') }}" alt="mission image">
                        </div>
                        <div class="service-content" style="height: 360px;">
                            <div class="service-shape" data-bg-src="{{ asset('nokw/assets/img/shape/sr-bg-shape-2-1.png') }}"></div>
                            <h3 class="service-title h5"><a>MISSION</a></h3>
                            <p class="service-text">
                                Mission of Nightingales of Kuwait (NOK) is to promote the welfare and unity of Indian nurses in Kuwait by enhancing their professional development, providing mutual support during times of need, organizing public health initiatives and contributing to the greater good through community outreach—guided by integrity, inclusivity and adherence to the laws of Kuwait and ethical healthcare practices.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="service-style2">
                        <div class="service-img" style="padding: 20px;">
                            <span class="service-number">02</span>
                            <img src="{{ asset('nokw/assets/img/service/2.png') }}" alt="vision image">
                        </div>
                        <div class="service-content" style="height: 360px;">
                            <div class="service-shape" data-bg-src="{{ asset('nokw/assets/img/shape/sr-bg-shape-2-1.png') }}"></div>
                            <h3 class="service-title h5"><a>VISION</a></h3>
                            <p class="service-text">
                                Vision of Nightingales of Kuwait (NOK) is to be a leading, unifying platform for Indian nurses in Kuwait—dedicated to professional excellence, compassionate service and the advancement of healthcare and community welfare while remaining non-political, non-religious and committed to ethical and lawful practice.
                            </p>
                        </div>
                    </div>
                </div>

               
            </div>
        </div>
    </div>

    <!--============================== Testimonial / Core values ==============================-->
    <section class="space-top space-extra-bottom" data-bg-src="{{ asset('nokw/assets/img/bg/process-bg-1-1.jpg') }}" id="processv1">
        <div class="container wow fadeInUp" data-wow-delay="0.2s">
            <div class="row justify-content-center text-center">
                <div class="col-xl-6">
                    <div class="title-area">
                        <span class="sec-subtitle">Core values</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3 process-style1">
                    <div class="process-icon">
                        <img src="{{ asset('nokw/assets/img/hero/1.png') }}" style="width: 95px;" alt="compassion icon">
                        <span class="process-number">01</span>
                    </div>
                    <h3 class="process-title h5">Compassion</h3>
                    <p class="process-text">Serving with empathy and kindness, respecting the dignity of every individual.</p>
                </div>

                <div class="col-sm-6 col-lg-3 process-style1">
                    <div class="process-icon">
                        <img src="{{ asset('nokw/assets/img/hero/2.png') }}" style="width: 95px;" alt="unity icon">
                        <span class="process-number">02</span>
                    </div>
                    <h3 class="process-title h5">Unity</h3>
                    <p class="process-text">Building a strong community where nurses support, collaborate, and grow together.</p>
                </div>

                <div class="col-sm-6 col-lg-3 process-style1">
                    <div class="process-icon">
                        <img src="{{ asset('nokw/assets/img/hero/3.png') }}" style="width: 95px;" alt="professionalism icon">
                        <span class="process-number">03</span>
                    </div>
                    <h3 class="process-title h5">Professionalism</h3>
                    <p class="process-text">Committed to excellence, ethics, and continuous learning in nursing practice.</p>
                </div>

                <div class="col-sm-6 col-lg-3 process-style1">
                    <div class="process-icon">
                        <img src="{{ asset('nokw/assets/img/hero/4.png') }}" style="width: 95px;" alt="service icon">
                        <span class="process-number">04</span>
                    </div>
                    <h3 class="process-title h5">Service</h3>
                    <p class="process-text">Dedicated to helping members and contributing to community health initiatives selflessly.</p>
                </div>
            </div>
        </div>
    </section>


@endsection
