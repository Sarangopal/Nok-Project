@extends('layouts.frontend')

@section('content')

<style>
#feature-style1 {
    margin-top: -42px !important;
}

</style>
<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>


<section>
    <div class="vs-carousel hero-layout1" data-slide-show="1" data-fade="true" data-arrows="true" data-autoplay="true" data-autoplay-speed="3000">

        <div>
            <div class="hero-inner">
                <div class="hero-shape1"></div>
                <div class="hero-bg" data-bg-src="{{ asset('nokw/assets/img/hero/banok1.jpg') }}"></div>
                <div class="hero-dark-overlay"></div>
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title"><span class="hero-innertext">Welcome to Nightingales of Kuwait</span><br>
                            Together We Care,<br> Together We Grow
                        </h1>
                        <p class="hero-text">
                            Nightingales of Kuwait brings Indian nurses together to foster professional growth,
                            community service, and cultural engagement — supporting each other, sharing knowledge,
                            and making a meaningful impact in society and healthcare.
                        </p>
                        <div class="hero-btns">
                            <a href="{{ url('about') }}" class="vs-btn">ABOUT US<i class="far fa-arrow-right"></i></a>
                            <a href="{{ url('contact') }}" class="vs-btn style2">CONTACT US<i class="far fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="hero-inner">
                <div class="hero-shape1"></div>
                <div class="hero-bg" data-bg-src="{{ asset('nokw/assets/img/hero/slider-2.jpeg') }}"></div>
                <div class="hero-dark-overlay"></div>
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title"><span class="hero-innertext">Strength in Unity</span><br>
                            Empowering Nurses,<br> Enriching Communities
                        </h1>
                        <p class="hero-text">
                            We unite Indian nurses to enhance skills, provide support, and engage in community
                            initiatives — fostering growth, solidarity, and positive impact within healthcare and society.
                        </p>
                        <div class="hero-btns">
                            <a href="{{ url('about') }}" class="vs-btn">ABOUT US<i class="far fa-arrow-right"></i></a>
                            <a href="{{ url('contact') }}" class="vs-btn style2">CONTACT US<i class="far fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Feature Section -->
<section class="feature-wrap1 space-top" id="feature-style1">
    <div class="container wow fadeInUp" data-wow-delay="0.2s">
        <div class="row vs-carousel" data-slide-show="3" data-lg-slide-show="2" data-md-slide-show="2">
            <div class="col-xl-4">
                <div class="feature-style1">
                    <div class="feature-icon"><img src="{{ asset('nokw/assets/img/skill/1.png') }}" alt="Features"></div>
                    <h3 class="feature-title h5">
                        <a class="text-inherit" >Professional Growth & Training</a>
                    </h3>
                    <p class="feature-text">
                        Enhancing nursing skills through education, workshops, and knowledge-sharing opportunities for every member.
                    </p>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="feature-style1">
                    <div class="feature-icon"><img src="{{ asset('nokw/assets/img/skill/2.png') }}" alt="Features"></div>
                    <h3 class="feature-title h5">
                        <a class="text-inherit" >Community Care & Outreach</a>
                    </h3>
                    <p class="feature-text">
                        Organizing health camps, awareness programs, and patient support initiatives to serve society with compassion.
                    </p>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="feature-style1">
                    <div class="feature-icon"><img src="{{ asset('nokw/assets/img/skill/3.png') }}" alt="Features"></div>
                    <h3 class="feature-title h5">
                        <a class="text-inherit" >Support & Solidarity</a>
                    </h3>
                    <p class="feature-text">
                        Standing by members during personal or global crises, fostering unity, dignity, and mutual support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!--<section class="events-section position-relative" style="padding-bottom: 50px;">-->
<!--  <div class="container z-index-common">-->
<!--    <div class="row gx-60">-->
      
      <!-- Column 8 - Latest Events Carousel -->
<!--      <div class="col-lg-8 col-xl-8 mb-50 mb-lg-0 wow fadeInUp" data-wow-delay="0.2s">-->
          
<!--        <div id="latest-events-slider" class="carousel slide events-slider" data-bs-ride="carousel">-->
          
          <!-- Carousel Inner -->
<!--          <div class="carousel-inner">-->
<!--            <div class="carousel-item active">-->
<!--              <img src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" class="events-img" alt="Event 1">-->
<!--            </div>-->
<!--            <div class="carousel-item">-->
<!--              <img src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" class="events-img" alt="Event 2">-->
<!--            </div>-->
<!--            <div class="carousel-item">-->
<!--              <img src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" class="events-img" alt="Event 3">-->
<!--            </div>-->
<!--          </div>-->
          
          <!-- Controls -->
<!--          <button class="carousel-control-prev custom-arrow" type="button" data-bs-target="#latest-events-slider" data-bs-slide="prev">-->
<!--            <i class="fas fa-chevron-left"></i>-->
<!--          </button>-->
<!--          <button class="carousel-control-next custom-arrow" type="button" data-bs-target="#latest-events-slider" data-bs-slide="next">-->
<!--            <i class="fas fa-chevron-right"></i>-->
<!--          </button>-->
<!--        </div>-->
<!--      </div>-->
      
      <!-- Column 4 - Sponsors & Advertisement -->
<!--      <div class="col-lg-4 col-xl-4" data-wow-delay="0.3s">-->
        <!-- Advertisement Box -->
<!--        <div class="ad-box">-->
<!--          <a href="#">-->
<!--            <img src="{{ asset('nokw/assets/img/gallery/gal-1-2.jpg') }}" class="ad-img" alt="Advertisement">-->
<!--          </a>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</section>-->


<section class="events-section position-relative" style="padding-bottom: 50px;">
  <div class="container z-index-common">
    <div class="row gx-60">
        
        <!-- Column 4 - Sponsors & Advertisement Carousel -->
      <div class="col-lg-4 col-xl-4 wow fadeInUp" data-wow-delay="0.3s">
        <h4 class="mb-4 fw-bold">Nightingales 2024</h4>

        <div id="sponsor-slider" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">

            <!-- Sponsor 1 -->
            <div class="carousel-item active">
              <div class="ad-box">
                <a href="#">
                  <img src="{{ asset('nokw/assets/img/project/nightingales2024/nok2024 (1).jpg') }}" class="ad-img" alt="Sponsor 1">
                </a>
              </div>
            </div>

            <!-- Sponsor 2 -->
            <div class="carousel-item">
              <div class="ad-box">
                <a href="#">
                  <img src="{{ asset('nokw/assets/img/project/nightingales2024/nok2024 (4).jpg') }}" class="ad-img" alt="Sponsor 2">
                </a>
              </div>
            </div>

            <!-- Sponsor 3 -->
            <div class="carousel-item">
              <div class="ad-box">
                <a href="#">
                  <img src="{{ asset('nokw/assets/img/project/nightingales2024/nok2024 (5).jpg') }}" class="ad-img" alt="Sponsor 3">
                </a>
              </div>
            </div>
          </div>

          <!-- Controls -->
          <button class="carousel-control-prev custom-arrow" type="button" data-bs-target="#sponsor-slider" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-control-next custom-arrow" type="button" data-bs-target="#sponsor-slider" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div><!-- Column 4 - Sponsors & Advertisement Carousel -->
      

      <!-- Column 8 - Latest Events Carousel -->
      <div class="col-lg-8 col-xl-8 mb-50 mb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
        
        <!-- Title -->
        <h4 class="mb-4 fw-bold">Upcoming Event</h4>

        <div id="latest-events-slider" class="carousel slide events-slider" data-bs-ride="carousel">
          
          <!-- Carousel Inner -->
          <div class="carousel-inner">

            @forelse($events as $index => $event)
              <!-- Dynamic Carousel Item {{ $index + 1 }} -->
              <div class="carousel-item {{ $index === 0 ? 'active' : '' }} position-relative">
                <div class="events-img-wrapper">
                  <img src="{{ $event->banner_image ? asset('storage/' . $event->banner_image) : asset('nokw/assets/img/event/nokkeve.jpeg') }}" 
                       class="events-img" 
                       alt="{{ $event->title }}">
                  <div class="event-caption">
                    <div class="caption-overlay"></div>
                    <h5 class="event-title">{{ $event->title }}</h5>
                    <p class="event-subtitle">
                      {{ $event->event_date ? $event->event_date->format('F d, Y') : 'Date TBA' }}
                      @if($event->location)
                        • {{ $event->location }}
                      @endif
                      @if($event->event_time)
                        • {{ $event->event_time }}
                      @endif
                    </p>
                  </div>
                </div>
              </div>
            @empty
              <!-- Fallback: No events available -->
              <div class="carousel-item active position-relative">
                <div class="events-img-wrapper">
                  <img src="{{ asset('nokw/assets/img/event/nokkeve.jpeg') }}" class="events-img" alt="Default Event">
                  <div class="event-caption">
                    <div class="caption-overlay"></div>
                    <h5 class="event-title">AARAVAM 2025</h5>
                    <p class="event-subtitle">September 26, 2025 • Aspire Bilingual School, Jleeb • 3 PM</p>
                  </div>
                </div>
              </div>
            @endforelse

          </div>

          <!-- Controls -->
          <button class="carousel-control-prev custom-arrow" type="button" data-bs-target="#latest-events-slider" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-control-next custom-arrow" type="button" data-bs-target="#latest-events-slider" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>

      

    </div>
  </div>
</section>

<style>
.events-img-wrapper {
  position: relative;
}
.events-img {
  width: 100%;
  display: block;
  border-radius: 10px;
}

/* Caption section with bottom-only overlay */
.event-caption {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 15px 20px;
  box-sizing: border-box;
  z-index: 2;
}
.caption-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  z-index: -1;
  border-radius: 0 0 10px 10px;
}
.event-title {
  font-size: 1.4rem;
  font-weight: bold;
  color: #fff !important;
  margin-bottom: 5px;
}
.event-subtitle {
  font-size: 1rem;
  color: #fff !important;
  opacity: 0.9;
}
.ad-box img.ad-img {
  width: 100%;
  border-radius: 10px;
}
</style>


<section class="position-relative space-bottom">
  <span class="about-shape1 d-none d-xl-block">Nightingales of Kuwait</span>
  <div class="container z-index-common">
      <div class="row gx-60">
          <div class="col-lg-6 col-xl-5 mb-50 mb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
              <div class="img-box1">
                  <div class="img-1">
                      <img src="{{ asset('nokw/assets/img/about/Untitled design (8).jpg') }}" alt="About image">
                  </div>
                  <div class="img-2">
                      <img src="{{ asset('nokw/assets/img/about/Untitled design (9).jpg') }}" alt="About image">
                      <a class="play-btn style2 position-center popup-video"
                          href="https://www.youtube.com/watch?v=E7Lbl5LUpK8"><i class=""><i class="fas fa-play"></i></i></a>
                  </div>
              </div>
          </div>
          <div class="col-lg-6 col-xl-7 align-self-center wow fadeInUp" data-wow-delay="0.3s">
              <span class="sec-subtitle"><i class="fas fa-bring-forward"></i>Who We Are</span>
              <h2 class="sec-title h1">About Nightingales of Kuwait</h2>
              <p class="mb-4 mt-1 pb-3">
                  Nightingales of Kuwait (NOK) is a non-profitable, non-political organization founded by Indian nurses officially registered under the Indian Embassy in Kuwait(Reg No: INDEMB/KWT/ASSN/401). Rooted in compassion and guided by unity, we aim to uplift the nursing community by fostering professional growth, offering support in times of need, and providing a platform for cultural and community engagement.
                  <br><br>
                  At NOK, we believe in the strength of togetherness—bringing Indian nurses together to build solidarity, enhance skills through training and education, and create meaningful impact within the profession and broader society.
              </p>
              <a href="{{ url('about') }}" class="vs-btn">About Us<i class="far fa-long-arrow-right"></i></a>
          </div>
      </div>
  </div>
</section>

<!--==============================
Counter Area
==============================-->
<div class="position-relative">
    <div class="counter-shape1"></div>
    <div class="bg-black z-index-common space bg-overlay" style="background-image: url('{{ asset('nokw/assets/img/bg/1.jpg') }}');">
        <div class="container wow fadeInUp" data-wow-delay="0.2s">
            <div class="row justify-content-between gy-4">

                <div class="col-6 col-lg-auto">
                    <div class="counter-media">
                        <div class="counter-media__icon">
                            <i class="fas fa-briefcase" style="color: #dad721; font-size: 40px;"></i>
                        </div>
                        <div class="media-body">
                            <span class="counter-media__number h1 text-white">10+</span>
                            <p class="counter-media__title text-white">Years of Service</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-auto">
                    <div class="counter-media">
                        <div class="counter-media__icon">
                            <i class="fas fa-users" style="color: #dad721; font-size: 40px;"></i>
                        </div>
                        <div class="media-body">
                            <span class="counter-media__number h1 text-white">1000+</span>
                            <p class="counter-media__title text-white">Members</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-auto">
                    <div class="counter-media">
                        <div class="counter-media__icon">
                            <i class="fas fa-calendar-check" style="color: #dad721; font-size: 40px;"></i>
                        </div>
                        <div class="media-body">
                            <span class="counter-media__number h1 text-white">50+</span>
                            <p class="counter-media__title text-white">Events Conducted</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-auto">
                    <div class="counter-media">
                        <div class="counter-media__icon">
                            <i class="fas fa-handshake" style="color: #dad721; font-size: 40px;"></i>
                        </div>
                        <div class="media-body">
                            <span class="counter-media__number h1 text-white">30+</span>
                            <p class="counter-media__title text-white">Community Programs</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!--==============================
Service Area
==============================-->
<section class="space-top" style="background-image: url('{{ asset('nokw/assets/img/bg/sr-bg-1-1.png') }}');">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8 col-xl-6 wow fadeInUp" data-wow-delay="0.2s">
                <div class="title-area">
                    <span class="sec-subtitle">Membership</span>
                    <h2 class="sec-title h1">Membership benefits</h2>
                </div>
            </div>
        </div>

        <div class="row wow fadeInUp" data-wow-delay="0.2s">
            @php
                $services = [
                    ['icon' => '1.png', 'title' => 'Continuing Education', 'desc' => 'Workshops, seminars and training programs to keep your skills and knowledge up to date.'],
                    ['icon' => '2.png', 'title' => 'Networking Opportunities', 'desc' => 'Meet fellow nurses, share ideas and build valuable professional and personal connections.'],
                    ['icon' => '3.png', 'title' => 'Recognition & Awards', 'desc' => 'Celebrate outstanding contributions and achievements within the nursing community through annual awards.'],
                    ['icon' => '4.png', 'title' => 'Community Service', 'desc' => 'Participate in health camps, awareness drives and outreach programs to serve society together.'],
                    ['icon' => '5.png', 'title' => 'Cultural & Family Events', 'desc' => 'Enjoy cultural gatherings, family meetups and entertainment designed to strengthen community bonds.'],
                    ['icon' => '6.png', 'title' => 'Support During Crisis', 'desc' => 'Receive assistance and moral support during health emergencies, financial hardships, or personal challenges.'],
                ];
            @endphp

            @foreach($services as $service)
            <div class="col-md-6 col-lg-4">
                <div class="service-style1">
                    <div class="service-bg" style="background-image: url('{{ asset('nokw/assets/img/bg/boxx.png') }}');"></div>
                    <div class="service-icon"><img src="{{ asset('nokw/assets/img/icon/' . $service['icon']) }}" alt="Features"></div>
                    <h3 class="service-title h5"><a href="{{ url('service-details') }}">{{ $service['title'] }}</a></h3>
                    <p class="service-text">{{ $service['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!--==============================
FAQ Area
==============================-->
<section class="faq-wrap1">
    <div class="faq-shape1" style="background-image: url('{{ asset('nokw/assets/img/bg/faq-bg-1-1.jpg') }}');"></div>
    <div class="faq-shape2" style="background-image: url('{{ asset('nokw/assets/img/bg/faq-bg-1-2.jpg') }}');"></div>
    <div class="container">
        <div class="row gx-60">
            <div class="col-lg-6 pb-20 pb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                <div class="img-box2">
                    <div class="img-1">
                        <img src="{{ asset('nokw/assets/img/committeee/Mr-Roy-yohannan.jpg') }}" alt="MR. ROY YOHANNAN" style="width: 780px; height:450px; object-fit:contain;">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 align-self-center">
                <!--<span class="sec-subtitle text-white"><i class="fas fa-bring-forward"></i>PATRON</span>-->
                <h2 class="sec-title text-white mb-4 pb-2 h1">Our Honorable Patron</h2>
                <p>"With the unwavering guidance and encouragement of our esteemed patrons, we strive to strengthen our community, foster professional excellence, and create meaningful initiatives that inspire, support, and uplift every member of the Nightingales of Kuwait family."</p>
                <h5 style="color: white;">MR. ROY YOHANNAN</h5>
            </div>
        </div>
    </div>
</section>

<style>
    .team-img img{
        height: 450px;
        object-fit: cover;
    }
</style>

<!--==============================
Team Area
==============================-->
<section class="space-top space-extra-bottom">
    <div class="container wow fadeInUp" data-wow-delay="0.2s">
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <div class="title-area">
                    <span class="sec-subtitle">Great Team Members</span>
                    <h2 class="sec-title h1">OFFICE BEARERS</h2>
                </div>
            </div>
        </div>

        @php
            $teamMembers = [
                ['name'=>'MR.SIJUMON THOMAS', 'role'=>'PRESIDENT', 'image'=>'Mr-Sijumon-Thomas.JPG'],
                ['name'=>'MRS.PRABHA RAVEENDRAN', 'role'=>'VICE PRESIDENT', 'image'=>'Mrs-Prabha-Raveendran.jpg'],
                ['name'=>'MRS.TEENA SUSAN THANKACHAN', 'role'=>'SECRETARY', 'image'=>'Mrs-Teena-susan-Thankachan.jpg'],
                ['name'=>'MR.SHYJU RAJAN', 'role'=>'JOINT SECRETARY', 'image'=>'Mr-Shyju-Rajan.jpg'],
                ['name'=>'MRS.TREESA ABRAHAM', 'role'=>'TREASURER', 'image'=>'Mrs-Treesa-Abraham.jpg'],
                ['name'=>'MR.MIDHUN ABRAHAM', 'role'=>'JOINT ACCOUNTS', 'image'=>'Mr-Midhun-Abraham.jpg'],
            ];
        @endphp

        <div class="row">
            @foreach($teamMembers as $member)
            <div class="col-xl-4">
                <div class="team-style1">
                    <div class="team-img">
                        <a href="#"><img src="{{ asset('nokw/assets/img/committeee/' . $member['image']) }}" alt="{{ $member['name'] }}"></a>
                    </div>
                    <div class="team-content">
                        <h3 class="team-title"><a class="text-inherit" href="#">{{ $member['name'] }}</a></h3>
                        <p class="team-degi">{{ $member['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align: center;">
    <a href="{{ url('executive_committee_25_26') }}" class="vs-btn">View All Members<i class="far fa-arrow-right"></i></a>
        </div>

        
    </div>
</section>

<!--==============================
CTA Area
==============================-->
<section class="z-index-common space bg-overlay" style="background-image: url('{{ asset('nokw/assets/img/bg/2.jpg') }}');">
    <div class="container">
        <div class="row text-center text-lg-start align-items-center justify-content-between">
            <div class="col-lg-auto">
                <span class="sec-subtitle text-white">Empower Your Journey, Connect with Nursing Community</span>
                <h2 class="h1 sec-title cta-title1">Join Us Today</h2>
            </div>
            <div class="col-lg-auto">
                <a href="{{ url('contact') }}" class="vs-btn">Register Now<i class="far fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!--==============================
Work Process
==============================-->
<section class="space-top space-extra-bottom" id="processv1" style="background-image: url('{{ asset('nokw/assets/img/bg/process-bg-1-1.jpg') }}');">
    <div class="container wow fadeInUp" data-wow-delay="0.2s">
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <div class="title-area">
                    <span class="sec-subtitle">Core values</span>
                </div>
            </div>
        </div>

        @php
            $processSteps = [
                ['number'=>'01', 'icon'=>'process-1-4.png', 'title'=>'Compassion', 'text'=>'Serving with empathy and kindness, respecting the dignity of every individual.'],
                ['number'=>'02', 'icon'=>'process-1-1.png', 'title'=>'Unity', 'text'=>'Building a strong community where nurses support, collaborate, and grow together.'],
                ['number'=>'03', 'icon'=>'process-1-2.png', 'title'=>'Professionalism', 'text'=>'Committed to excellence, ethics, and continuous learning in nursing practice.'],
                ['number'=>'04', 'icon'=>'process-1-3.png', 'title'=>'Service', 'text'=>'Dedicated to helping members and contributing to community health initiatives selflessly.'],
            ];
        @endphp

        <div class="row">
            @foreach($processSteps as $step)
            <div class="col-sm-6 col-lg-3 process-style1">
                <div class="process-icon">
                    <img src="{{ asset('nokw/assets/img/icon/' . $step['icon']) }}" alt="icon">
                    <span class="process-number">{{ $step['number'] }}</span>
                </div>
                <h3 class="process-title h5">{{ $step['title'] }}</h3>
                <p class="process-text">{{ $step['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!--==============================
CTA Wrap 3
==============================-->
<div class="cta-wrap3">
    <div class="container">
        <div class="row gx-0">
            <div class="col-xl-6">
                <div class="cta-style1" style="background-image: url('{{ asset('nokw/assets/img/bg/cta-bg-4-1.jpg') }}');" data-overlay="theme" data-opacity="8">
                    <h5 style="color:white;">Book Your Space</h5>
                    <h2 class="cta-title">For</h2>
                    <h3 class="cta-title2">Advertising</h3>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="cta-style1 bg-center" style="background-image: url('{{ asset('nokw/assets/img/bg/cta-bg-4-2.png') }}');">
                    <h5 style="color:white;">Book Your Space</h5>
                    <h2 class="cta-title">For</h2>
                    <h3 class="cta-title2">Advertising</h3>
                </div>
            </div>
        </div>
    </div>
</div>

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
