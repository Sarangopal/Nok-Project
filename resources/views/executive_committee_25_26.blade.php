@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Executive Committe | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>   <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Executive Committee</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Executive Committee</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!--==============================
Team Area
===============================-->

<section class="space-top">
    <div class="container">
        <div class="row gx-60 gy-2">
            <div class="col-lg-5 mb-30 wow fadeInUp" data-wow-delay="0.2s">
                <img src="{{ asset('nokw/assets/img/committeee/Mr-Roy-yohannan.jpg') }}" style="height: 500px; object-fit: cover;" alt="Mr Roy Yohannan, Patron">
            </div>
            <div class="col-lg-7 col-xl-7 align-self-center mb-30">
                <div class="team-about">
                    <span class="sec-subtitle"><i class="fas fa-bring-forward"></i>Executive Committee for the Year 2025-2026</span>
                    <h3 class="team-name h4">Patron</h3>
                    <p class="team-text" style="padding-top: 30px;">
                        Our Patron serves as the guiding light and inspiration behind all our initiatives. With a deep commitment to the welfare and empowerment of the nursing community, our Patron provides invaluable support and encouragement that drives our organization forward. Their vision ensures that we continue to uphold our mission of fostering unity, professional growth, and cultural harmony among our members.
                    </p>
                    <p style="padding-bottom: 20px;">
                        Beyond being a symbol of leadership, our Patron actively champions our causes, offering wisdom and direction that help us create meaningful programs and events. Their presence is a constant reminder of our shared purpose – to uplift, empower, and celebrate every member of the Nightingales of Kuwait community.
                    </p>
                    <h4 class="team-name h5" style="color: #0e5af2;">Mr. Roy Yohannan</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.team-img img {
    /*width: 283px;*/
    height: 365px;
    object-fit: contain;
}
</style>

@php
$committees = [
    'Office Bearers' => [
        ['name' => 'Mr. Sijumon Thomas', 'designation' => 'President', 'image' => 'Mr-Sijumon-Thomas.JPG'],
        ['name' => 'Mrs. Prabha Raveendran', 'designation' => 'Vice President', 'image' => 'Mrs-Prabha-Raveendran.jpg'],
        ['name' => 'Mrs. Teena Susan Thankachan', 'designation' => 'Secretary', 'image' => 'Mrs-Teena-susan-Thankachan.jpg'],
        ['name' => 'Mr. Shyju Rajan', 'designation' => 'Joint Secretary', 'image' => 'Mr-Shyju-Rajan.jpg'],
        ['name' => 'Mrs. Treesa Abraham', 'designation' => 'Treasurer', 'image' => 'Mrs-Treesa-Abraham.jpg'],
        ['name' => 'Mr. Midhun Abraham', 'designation' => 'Joint Accounts', 'image' => 'Mr-Midhun-Abraham.jpg'],
    ],
    'Core Committee' => [
        ['name' => 'Mr. Sijumon Thomas', 'image' => 'Mr-Sijumon-Thomas.JPG'],
        ['name' => 'Mrs. Teena Susan Thankachan', 'image' => 'Mrs-Teena-susan-Thankachan.jpg'],
        ['name' => 'Mrs. Treesa Abraham', 'image' => 'Mrs-Treesa-Abraham.jpg'],
        ['name' => 'Mr. Ciril B Mathew', 'image' => 'Mr-Ciril-B-Mathew.jpg'],
        ['name' => 'Mrs. Sumi John', 'image' => 'Mrs-Sumi-John.jpg'],
        ['name' => 'Mrs. Soumya Abraham', 'image' => 'Mrs-Soumya-Abraham.jpg'],
        ['name' => 'Mr. Sudesh Sudhakar', 'image' => 'Mr-Sudesh-Sudhakar.jpg'],
        ['name' => 'Mr. Aby Chacko Thomas', 'image' => 'Aby-Chacko-Thomas.JPG'],
        ['name' => 'Mr. Sobin Thomas', 'image' => 'Mr-Sobin-Thomas.jpg'],
        ['name' => 'Mr. Nitheesh Narayanan', 'image' => 'Mr-Nitheesh-Narayanan.JPG'],
    ],
    'Auditors' => [
        ['name' => 'Mrs. Bindu Thankachan', 'designation' => 'Auditor', 'image' => 'Mrs-Bindu-Thankachan.jpg'],
        ['name' => 'Mr. Dantis Thomas', 'designation' => 'Reserve Auditor', 'image' => 'Mr-Dantis-Thomas.jpg'],
    ],
    'Program Committee' => [
        ['name' => 'Mrs. Seema Francis', 'designation' => 'Coordinator', 'image' => 'Mrs-Seema-Francis.jpg'],
        ['name' => 'Mrs. Roshin Merin Renjit', 'designation' => 'Sub-Coordinator', 'image' => 'Mrs-Roshin-Merin-Renjit.jpg'],
        ['name' => 'Mr. Peeter Mampilly Paulose', 'designation' => 'Member 1', 'image' => 'Mr-Peeter-Mampilly-Paulose.JPG'],
        ['name' => 'Mrs. Soniya Jose', 'designation' => 'Member 2', 'image' => 'Mrs-Soniya-Jose.jpg'],
    ],
    'Membership Committee' => [
        ['name' => 'Mrs. Sonia Thomas', 'designation' => 'Coordinator', 'image' => 'Mrs-Sonia-Thomas.jpg'],
        ['name' => 'Mrs. Shirin Varghis', 'designation' => 'Coordinator', 'image' => 'Mrs-Shirin-Varghis.jpg'],
        ['name' => 'Mr. Rojish Skariah', 'designation' => 'Sub-Coordinator', 'image' => 'Mr-Rojish-Skariah.jpg'],
        ['name' => 'Mrs. Christina Chotharayil Uthup', 'designation' => 'Member 1', 'image' => 'Mrs-Christina-Chotharayil.jpg'],
        ['name' => 'Mrs. Preetha Thomas', 'designation' => 'Member 2', 'image' => 'Mrs-Preetha-Thomas.jpg'],
    ],
    'Public Relations & Media Committee' => [
        ['name' => 'Mrs. Sheeja Thomas', 'designation' => 'Coordinator', 'image' => 'Mrs-sheeja-Thomas.JPG'],
        ['name' => 'Mr. Jimmy Kuriakose', 'designation' => 'Media', 'image' => 'Mr-Jimmy-Kuriakose.jpg'],
        ['name' => 'Mr. Denny Thomson', 'designation' => 'Public Relations', 'image' => 'Mr-DennyThomas.jpg'],
        ['name' => 'Mr. Deepak Thomas', 'designation' => 'Member 1', 'image' => 'Mr-Deepak-Thomas.PNG'],
        ['name' => 'Mrs. Anu Ciril', 'designation' => 'Member 2', 'image' => 'Mrs-Anu-Cyril.jpg'],
    ],
    'Social Welfare Committee' => [
        ['name' => 'Mrs. Bindhumol Subash', 'designation' => 'Coordinator', 'image' => 'Mrs-Bindhumol-Subhash.jpg'],
        ['name' => 'Mrs. Vidhu Rose Vincent', 'designation' => 'Sub-Coordinator', 'image' => 'Mrs-Vidhu-Rose-Vincent.jpg'],
        ['name' => 'Mr. Justin Xavier', 'designation' => 'Member 1', 'image' => 'Mr-Justin-Xavier.jpg'],
        ['name' => 'Mr. Anish K Mohanan', 'designation' => 'Member 2', 'image' => 'Mr-Anish-K-Mohanan.jpg'],
    ],
    'Arts & Sports Committee' => [
        ['name' => 'Mr. Sarath Kumar Sasidharan', 'designation' => 'Coordinator', 'image' => 'Mr-Sarath-Kumar-Sasidharan.PNG'],
        ['name' => 'Mr. Thamseeth Pattillath Mohamed', 'designation' => 'Sports', 'image' => 'Mr-Thamseeth-Pattillath-Mohamed.JPG'],
        ['name' => 'Mr. Hareesh Parokkottu Veettil', 'designation' => 'Arts', 'image' => 'Mr-Hareesh-Parakkottu-Veettil.PNG'],
        ['name' => 'Mr. Lijo John', 'designation' => 'Member 1', 'image' => 'Mr-Lijo-John.jpg'],
        ['name' => 'Mr. Chinnappa Dhas', 'designation' => 'Member 2', 'image' => 'Mr-Chinnappa-Dhas.jpg'],
    ],
];
@endphp

<section class="space-top space-extra-bottom">
    <div class="container wow fadeInUp" data-wow-delay="0.2s">
        @foreach($committees as $committeeName => $members)
            <h2 class="sec-title3 h1" style="text-transform: uppercase; padding-top: 20px;">{{ $committeeName }}</h2>
            <div class="row" style="padding-top: 20px;">
                @foreach($members as $member)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="team-style1">
                            <div class="team-img">
                                <a><img src="{{ asset('nokw/assets/img/committeee/' . $member['image']) }}" alt="{{ $member['name'] }}"></a>
                            </div>
                            <div class="team-content">
                                <h3 class="team-title"><a class="text-inherit" style="text-transform: uppercase;">{{ $member['name'] }}</a></h3>
                                @isset($member['designation'])
                                    <p class="team-degi" style="text-transform: uppercase;">{{ $member['designation'] }}</p>
                                @endisset
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>




@endsection
