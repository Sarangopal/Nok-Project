@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'About | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>    

 <!--============================== Breadcumb ==============================-->
    <div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Our Logo</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Our Logo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!--============================== Column Elements ==============================-->
    <div class="space1" style="padding: 80px 0px 40px 0px;">
        <div class="container">
            <div class="row gy-30">
                <!-- First Column: Image -->
                <div class="col-lg-6">
                    <div class="p-3 p-lg-5">
                        <img src="{{ asset('nokw/assets/img/nok_logo.PNG') }}" alt="NOK Logo" style="width:100%; border-radius:10px;">
                    </div>
                </div>

                <!-- Second Column: Updated Content -->
                <div class="col-lg-6 bg-smoke">
                    <div class="p-lg-5">
                        <h4>Our Logo and Its Significance</h4>
                        <p>
                            The official logo of the Nightingales of Kuwait was thoughtfully designed by one of our esteemed members,
                            <strong>Mr. Shithin Chandy Varghese</strong>, a dedicated nurse from the Emergency Department at Farwaniya Hospital.
                        </p>
                        <p>
                            His creative design beautifully reflects the mission and vision of the association—symbolizing unity,
                            compassion, professionalism, and the noble spirit of nursing. The logo stands as a visual representation of
                            our core values and the collective identity of nurses who serve with commitment and care across Kuwait.
                        </p>
                        <p>
                            We proudly acknowledge and appreciate Mr. Shithin’s contribution, which continues to inspire and
                            represent our community.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space bg-smoke">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Intro Column -->
                <div class="col-lg-12">
                    <div class="bg-white p-3 p-lg-5">
                        <h3>The Logo of NOK</h3>
                        <p>
                            The logo of the Nightingales of Kuwait (NOK) consists of five symbolic elements:
                            a saker falcon, a heart with ECG rhythm, a caring hand, an inner circle, and an outer circle.
                            Each component holds a unique significance that reflects the values and mission of the association.
                        </p>
                    </div>
                </div>

                <!-- Symbolic Elements -->
                <div class="col-lg-12">
                    <div class="bg-white p-3 p-lg-5">
                        <h4>Symbolic Elements</h4>

                        <!-- Row 1 -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('nokw/assets/img/2.png') }}" alt="Saker Falcon" style="width:200px; border-radius:8px;">
                                <h6 class="mt-2">Saker Falcon</h6>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    The saker falcon, the national bird of Kuwait, symbolizes strength, grace, and nobility.
                                    Its presence in the logo represents the association’s foundation and existence in Kuwait,
                                    reflecting the dignity and resilience of the Nightingales of Kuwait.
                                </p>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('nokw/assets/img/3.png') }}" alt="Heart with ECG Rhythm" style="width:200px; border-radius:8px;">
                                <h6 class="mt-2">Heart with ECG Rhythm</h6>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    The heart and ECG rhythm signify life and health, symbolizing the vital role nurses play
                                    in the well-being of the people of Kuwait. It also represents the unity of Indian nurses
                                    under NOK, dedicated to the welfare of both the nursing community and the public.
                                </p>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('nokw/assets/img/4.png') }}" alt="Caring Hand" style="width:200px; border-radius:8px;">
                                <h6 class="mt-2">Caring Hand</h6>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    The caring hand embodies the compassion and service of nurses. It illustrates the association’s
                                    mission to extend support and care to its members and the public, highlighting the relationship
                                    between nurses, patients, and society.
                                </p>
                            </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('nokw/assets/img/5.png') }}" alt="Inner Circle" style="width:200px; border-radius:8px;">
                                <h6 class="mt-2">Inner Circle</h6>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    The inner circle represents the commitment of the association to act in strict accordance with
                                    the approved bylaws. It signifies discipline, integrity, and purpose in all organizational actions.
                                </p>
                            </div>
                        </div>

                        <!-- Row 5 -->
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('nokw/assets/img/6.png') }}" alt="Outer Circle" style="width:200px; border-radius:8px;">
                                <h6 class="mt-2">Outer Circle</h6>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    The outer circle reflects the association’s adherence to the rules and regulations of both Kuwait
                                    and India. It symbolizes accountability, legality, and respect for governing frameworks.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
