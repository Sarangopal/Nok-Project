@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Contact | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>    
    <!-- Breadcumb -->
    <div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Contact Us</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('contact') }}">Home</a></li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form Area -->
    <section class="space-top space-extra-bottom">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-6 mb-30">
                    <div class="contact-box">
                        <h3 class="contact-box__title h4">Let’s Stay Connected</h3>
                        <p class="contact-box__text">
                            Reach out to us for inquiries, support, or collaboration — we’re here to help you.
                        </p>

                        <div class="contact-box__item">
                            <div class="contact-box__icon"><i class="fal fa-phone-alt"></i></div>
                            <div class="media-body">
                                <h4 class="contact-box__label">Phone Number</h4>
                                <p class="contact-box__info">
                                    <a href="tel:+96566534053">+965 6653 4053</a>
                                    <!--<a href="tel:+919745779800">+91 9745779800</a>-->
                                </p>
                            </div>
                        </div>

                        <div class="contact-box__item">
                            <div class="contact-box__icon"><i class="fal fa-envelope"></i></div>
                            <div class="media-body">
                                <h4 class="contact-box__label">Email</h4>
                                <p class="contact-box__info">
                                    <a href="mailto:nightingalesofkuwait24@gmail.com">
                                        nightingalesofkuwait24@gmail.com
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="contact-box__item">
                            <div class="contact-box__icon"><i class="far fa-map-marker-alt"></i></div>
                            <div class="media-body">
                                <h4 class="contact-box__label">Our Office Address</h4>
                                <p class="contact-box__info">
                                    Farwaniya, Post Box No 13373, Kuwait
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-6 mb-30">
                    <div class="contact-box">
                        <h3 class="contact-box__title h4">Leave a Message</h3>
                        <p class="contact-box__text">We’re Ready To Help You</p>
                        <form class="contact-box__form" method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="row gx-20">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}">
                                    <i class="fal fa-user"></i>
                                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                                    <i class="fal fa-envelope"></i>
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <input type="tel" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                                    <i class="fal fa-phone"></i>
                                </div>

                                <div class="col-md-6 form-group">
                                    <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}">
                                    <i class="fal fa-tag"></i>
                                    @error('subject')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                <div class="col-12 form-group">
                                    <textarea name="message" placeholder="Type Your Message">{{ old('message') }}</textarea>
                                    @error('message')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                <div class="col-12">
                                    <button class="vs-btn">Submit Message <i class="far fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
