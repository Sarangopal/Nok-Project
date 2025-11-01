@include('partials.whatsapp-widget')
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
    @media (max-width: 576px) {
  .footer-info_group {
    padding: 0 !important;
  }
}
</style>
<footer class="footer-wrapper footer-layout1 custom-footer-bg">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                {{-- Office Address --}}
                <div class="col-sm footer-info_group">
                    <div class="footer-info">
                        <div class="footer-info_icon"><i class="fal fa-map-marker-alt"></i></div>
                        <div class="media-body">
                            <span class="footer-info_label">Office Address</span>
                            <div class="footer-info_link">Farwaniya, Post Box No 13373, Kuwait</div>
                        </div>
                    </div>
                </div>

                {{-- Mail --}}
                <div class="col-sm footer-info_group" style="padding-right: 40px;">
                    <div class="footer-info">
                        <div class="footer-info_icon"><i class="fal fa-envelope"></i></div>
                        <div class="media-body">
                            <span class="footer-info_label">Mail us</span>
                            <div class="footer-info_link">
                                <a href="mailto:nightingalesofkuwait24@gmail.com">
                                    nightingalesofkuwait24@gmail.com
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="col-sm footer-info_group">
                    <div class="footer-info">
                        <div class="footer-info_icon"><i class="fal fa-phone-volume"></i></div>
                        <div class="media-body">
                            <span class="footer-info_label">Contact Us</span>
                            <div class="footer-info_link">
                                <a href="tel:+96566534053">+965 6653 4053</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Widgets --}}
    <div class="widget-area">
        <div class="container">
            <div class="row justify-content-between">
                {{-- About Us --}}
                <div class="col-md-6 col-lg-4 col-xl-auto">
                    <div class="widget footer-widget">
                        <h3 class="widget_title">About Us</h3>
                        <div class="vs-widget-about">
                            <p class="footer-text">
                                Nightingales of Kuwait unites Indian nurses,
                                fostering growth, compassion, and community service across Kuwait.
                            </p>
                            <div class="footer-social">
                                <a href="https://facebook.com/groups/1582427865174367/?mibextid=rS40aB7S9Ucbxw6v"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/nightingales_of_kuwait_nok?igsh=MThtMmJ5ZmdlZW43ZA=="><i class="fab fa-instagram"></i></a>
                                <a href="https://youtube.com/@nok-2024?si=6LgAIBV-WlAfIbtR"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-4 col-lg-2 col-xl-auto">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title">Quick Links</h3>
                        <ul class="menu">
                            <li><a href="{{ url('/') }}"><i class="fas fa-angle-right me-1"></i> Home</a></li>
                            <li><a href="{{ url('/about') }}"><i class="fas fa-angle-right me-1"></i> About Us</a></li>
                            <li><a href="{{ url('/about#mission') }}"><i class="fas fa-angle-right me-1"></i> Mission & Vision</a></li>
                            <li><a href="{{ url('/core_values') }}"><i class="fas fa-angle-right me-1"></i> Core Values</a></li>
                            <li><a href="{{ url('/founding_of_nok') }}"><i class="fas fa-angle-right me-1"></i> Founding of NOK</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Committee --}}
                <div class="col-4 col-lg-2 col-xl-auto">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title">Committee</h3>
                        <ul class="menu">
                            <li><a href="{{ url('/executive_committee_25_26') }}"><i class="fas fa-angle-right me-1"></i> Committee 2025-2026</a></li>
                            <li><a href="{{ url('/executive_committee') }}"><i class="fas fa-angle-right me-1"></i> Former Committees</a></li>
                            <li><a href="{{ url('/patrons_message') }}"><i class="fas fa-angle-right me-1"></i> Patron’s Message</a></li>
                            <li><a href="{{ url('/presidents_message') }}"><i class="fas fa-angle-right me-1"></i> President’s Message</a></li>
                            <li><a href="{{ url('/secretarys_message') }}"><i class="fas fa-angle-right me-1"></i> Secretary’s Message</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Explore --}}
                <div class="col-4 col-lg-2 col-xl-auto">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title">Explore</h3>
                        <ul class="menu">
                            <li><a href="{{ url('/our_logo') }}"><i class="fas fa-angle-right me-1"></i> Our Logo</a></li>
                            <li><a href="{{ url('/events') }}"><i class="fas fa-angle-right me-1"></i> Events</a></li>
                            <li><a href="{{ url('/gallery') }}"><i class="fas fa-angle-right me-1"></i> Gallery</a></li>
                            <li><a href="{{ url('/contact') }}"><i class="fas fa-angle-right me-1"></i> Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="copyright-wrap">
        <div class="container">
            <p class="copyright-text">
                Copyright <i class="fal fa-copyright"></i>
                {{ date('Y') }}
                <a class="text-white" href="{{ url('/') }}">nokkw.org</a>.
                All rights reserved.
            </p>
        </div>
    </div>
</footer>

    <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>