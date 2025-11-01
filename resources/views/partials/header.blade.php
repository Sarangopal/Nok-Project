<!-- ==============================
     Preloader
============================== -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>

<!-- ==============================
    Mobile Menu
============================== -->
<div class="vs-menu-wrapper">
    <div class="vs-menu-area text-center">
        <button class="vs-menu-toggle"><i class="fal fa-times"></i></button>
        <div class="mobile-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('nokw/assets/img/NOK.png') }}" alt="NOK" class="logo">
            </a>
        </div>
        <div class="vs-mobile-menu">
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>

                <li class="menu-item-has-children"><a href="{{ url('/about') }}">About us</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/founding_of_nok') }}">Founding of NOK</a></li>
                        <li><a href="{{ url('/our_logo') }}">Our logo</a></li>
                        <li><a href="{{ url('/core_values') }}">Core Values</a></li>
                        <li><a href="{{ url('/patrons_message') }}">Patron’s Message</a></li>
                        <li><a href="{{ url('/presidents_message') }}">President’s Message</a></li>
                        <li><a href="{{ url('/secretarys_message') }}">Secretary’s Message</a></li>
                        <li><a href="{{ url('/treasurer_message') }}">Treasurer Message</a></li>
                        <li><a href="{{ url('/executive_committee') }}">Former Committees</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/executive_committee_25_26') }}">Executive Committee 2025-2026</a></li>
                <li><a href="{{ url('/events') }}">Events</a></li>
                <li><a href="{{ url('/gallery') }}">Gallery</a></li>
                
                <li><a href="{{ url('/contact') }}">Contact</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- ==============================
        Header Area
============================== -->
<header class="vs-header header-layout1">
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center justify-content-between gy-1 text-center text-lg-start">
                <div class="col-lg-auto d-none d-lg-block">
                    <p class="header-text">
                        <span class="fw-medium">Latest updates:</span>
                        <span class="marque1">
                            <span class="marquee__inner">
                                <span class="marquee__text">
                                    🎉 AARAVAM 2025 – September 26, Aspire Bilingual School, Jleeb | 3:00 PM onwards | Celebrate unity, culture, and togetherness with us! 🎶
                                </span>
                                <span class="marquee__text">
                                    🎉 AARAVAM 2025 – September 26, Aspire Bilingual School, Jleeb | 3:00 PM onwards | Celebrate unity, culture, and togetherness with us! 🎶
                                </span>
                            </span>
                        </span>                                
                    </p>
                </div>

                <div class="col-lg-auto">
                    <div class="header-social style-white">
                        <span class="social-title">Follow Us On: </span>
                            <a href="https://facebook.com/groups/1582427865174367/?mibextid=rS40aB7S9Ucbxw6v"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/nightingales_of_kuwait_nok?igsh=MThtMmJ5ZmdlZW43ZA=="><i class="fab fa-instagram"></i></a>
                            <a href="https://youtube.com/@nok-2024?si=6LgAIBV-WlAfIbtR"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="container">
        <div class="menu-top">
            <div class="row justify-content-between align-items-center gx-sm-0">
                <div class="col">
                    <div class="header-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('nokw/assets/img/NOK.png') }}" style="width: 110px;" alt="NOK" class="logo">
                        </a>
                    </div>
                </div>

                <div class="col-auto header-info">
                    <div class="header-info_icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="media-body">
                        <span class="header-info_label">Call Us</span>    
                        <div class="header-info_link"><a href="tel:+96566534053">+965 6653 4053</a></div>
                    </div>
                </div>

                <div class="col-auto header-info d-none d-lg-flex">
                    <div class="header-info_icon"><i class="fas fa-envelope"></i></div>
                    <div class="media-body">
                        <span class="header-info_label">Mail Us</span>
                        <div class="header-info_link">
                            <a href="mailto:nightingalesofkuwait24@gmail.com">nightingalesofkuwait24@gmail.com</a>
                        </div>
                    </div>
                </div>

                <div class="col-auto header-info d-none d-xl-flex">
                    <div class="header-info_icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="media-body">
                        <span class="header-info_label">Reach us</span>
                        <div class="header-info_link">Farwaniya, Kuwait</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Main Menu -->
    <div class="sticky-wrapper">
        <div class="sticky-active">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <nav class="main-menu menu-style1 d-none d-lg-block">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li class="menu-item-has-children"><a href="{{ url('/about') }}">About us</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ url('/about') }}">About NOK</a></li>
                                        <li><a href="{{ url('/founding_of_nok') }}">Founding of NOK</a></li>
                                        <li><a href="{{ url('/our_logo') }}">Our logo</a></li>
                                        <li><a href="{{ url('/core_values') }}">Core Values</a></li>
                                        <li><a href="{{ url('/patrons_message') }}">Patron’s Message</a></li>
                                        <li><a href="{{ url('/presidents_message') }}">President’s Message</a></li>
                                        <li><a href="{{ url('/secretarys_message') }}">Secretary’s Message</a></li>
                                        <li><a href="{{ url('/treasurer_message') }}">Treasurer Message</a></li>
                                        <li><a href="{{ url('/executive_committee') }}">Former Committees</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('/executive_committee_25_26') }}">Executive Committee 2025-2026</a></li>
                                <li><a href="{{ url('/events') }}">Events</a></li>
                                <li><a href="{{ url('/gallery') }}">Gallery</a></li>
                                
                                <li><a href="{{ url('/contact') }}">Contact</a></li>
                            </ul>
                        </nav>
                        <button class="vs-menu-toggle d-inline-block d-lg-none"><i class="fal fa-bars"></i></button>
                    </div>

                    <div class="col-auto">
                        <a href="{{ url('/registration') }}" class="vs-btn style3 ls-hero-btn">
                            <i class="fas fa-user" style="margin-right: 8px;"></i>
                            Register
                            <i class="far fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('verify-member.form') }}" class="vs-btn style3 ls-hero-btn">
    <i class="fas fa-sync-alt" style="margin-right: 8px;"></i>
    Verify
    <i class="far fa-arrow-right"></i>
</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
