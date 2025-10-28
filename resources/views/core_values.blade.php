@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Core Values | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>    <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Core Values</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Core Values</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="bg-smoke">
    <div class="container space-top space-extra-bottom">
        <span class="sec-subtitle"><i class="fas fa-bring-forward"></i> Core Values</span>
        <h2 class="sec-title3 h1">Core Values of Nightingales of Kuwait (NOK)</h2>
        <div class="row">
            @for ($i = 1; $i <= 9; $i++)
                <div class="col-lg-6">
                    <div class="service-style2">
                        <div class="service-img">
                            <span class="service-number">{{ sprintf('%02d', $i) }}</span>
                            <img src="{{ asset('nokw/assets/img/service/' . $i . '.jpg') }}" alt="image">
                        </div>
                        <div class="service-content">
                            <div class="service-shape" data-bg-src="{{ asset('nokw/assets/img/shape/sr-bg-shape-2-1.png') }}"></div>
                            @php
                                $titles = [
                                    'Compassion',
                                    'Unity',
                                    'Professionalism',
                                    'Service',
                                    'Integrity',
                                    'Empowerment',
                                    'Inclusivity',
                                    'Resilience',
                                    'Compliance and Governance'
                                ];
                                $texts = [
                                    'We serve with empathy, kindness and a deep commitment to the dignity and wellbeing of every individual.',
                                    'We foster a strong sense of solidarity and mutual respect among Indian nurses, working together as one supportive community.',
                                    'We uphold the highest standards of ethical conduct, clinical excellence  and continuous learning in all that we do.',
                                    'We are dedicated to selfless service—supporting members in need and contributing meaningfully to the healthcare needs of the wider community.',
                                    'We operate with transparency, honesty and accountability in our actions, decision and commitments.',
                                    'We create opportunities for personal and professional growth through education, skill development and leadership support.',
                                    'We value diversity and ensure our programs and services are free from political, religious or any discriminatory influence.',
                                    'We stand strong in times of crisis—providing support during health emergencies, financial hardship or natural disasters.',
                                    'All actions and activities of NOK are governed by its bylaws and conducted in strict adherence to the rules and regulations of the Ministry of Health – State of Kuwait, the Government of Kuwait, and the Embassy of India.'
                                ];
                            @endphp
                            <h3 class="service-title h5"><a href="#">{{ $titles[$i-1] }}</a></h3>
                            <p class="service-text">{{ $texts[$i-1] }}</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>



@endsection
