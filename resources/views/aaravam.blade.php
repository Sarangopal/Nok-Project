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
            <h1 class="breadcumb-title">Event Details</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Event Details</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--==============================
    Blog Area
==============================-->
<section class="vs-blog-wrapper blog-details space-top space-extra-bottom">
    <div class="container">
        <div class="row gx-40">
            <div class="col-lg-8">
                <div class="vs-blog blog-single">
                    <div class="blog-img">
                        <img src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" style="width: 803px; height: 550px; object-fit: cover;" alt="Blog Image">
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <a href="#"><i class="far fa-calendar"></i>September 26, 2025</a>
                            <a href="#"><i class="fal fa-user"></i>by admin</a>
                        </div>
                        <h2 class="blog-title">AARAVAM 2025</h2>
                        <p>Date: Friday, September 26, 2025</p>
                        <p>Time: 3:00 PM onwards</p>
                        <p>Venue: Aspire Bilingual School, Jleeb</p>
                        <p>AARAVAM 2025 marks our grand Onam celebration, bringing the Indian nursing community and their families together to cherish Kerala’s most loved festival. This colorful gathering will showcase the true spirit of Onam with traditional festivities, cultural performances, and moments of joy that reflect unity and togetherness.</p>
                    </div>
                    <!--<div class="share-links clearfix">-->
                    <!--    <div class="row justify-content-between">-->
                    <!--        <div class="col-auto">-->
                    <!--            <span class="share-links-title">Tags</span>-->
                    <!--            <div class="tagcloud">-->
                    <!--                <a href="#">NOK 2025</a>-->
                    <!--                <a href="#">NIGHTINGALES</a>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        <div class="col-auto text-end">-->
                    <!--            <span class="share-links-title">Social Icon</span>-->
                    <!--            <ul class="social-links">-->
                    <!--                <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>-->
                    <!--                <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>-->
                    <!--                <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>-->
                    <!--                <li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li>-->
                    <!--            </ul>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div>
            <div class="col-lg-4">
                <aside class="sidebar-area">
                    <div class="widget widget_categories1">
                        <h3 class="widget_title">Advertisement</h3>
                        <div class="ad-space">
                            <a href="#" target="_blank">
                                <img src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" alt="Advertisement" style="width:100%; border-radius:10px;">
                            </a>
                        </div>
                    </div>

                    <div class="widget">
                        <h3 class="widget_title">Upcoming events</h3>
                        <div class="recent-post-wrap">
                            <div class="recent-post">
                                <div class="media-img">
                                    <a href="#"><img style="width: 80px; height: 80px; object-fit: cover;" src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" alt="Blog Image"></a>
                                </div>
                                <div class="media-body">
                                    <h4 class="post-title"><a class="text-inherit" href="#">AARAVAM 2025</a></h4>
                                    <div class="recent-post-meta">
                                        <a href="#">September 26, 2025</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget widget_tag_cloud">
                        <h3 class="widget_title">Contact Us</h3>
                        <div class="tagcloud">
                            <p><strong>Mobile (UAE):</strong><a href="tel:+96566534053">+965 6653 4053</a></p>
                            <!--<p><strong>Mobile(INDIA):</strong> <a href="tel:+919745779800">+91 9745779800</a></p>-->
                            <p><strong>Email:</strong> <a href="mailto:nightingalesofkuwait24@gmail.com">nightingalesofkuwait24@gmail.com</a></p>
                        </div>
                    </div>
                </aside>
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
