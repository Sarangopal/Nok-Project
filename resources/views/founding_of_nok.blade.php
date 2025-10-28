@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Executive Committe | Nightingales of Kuwait')

@section('content')


<style>
.team-img img {
        width: 100%;
        /*height: 280px;*/
        height: 320px;
        object-fit: cover; /* This is the key. It makes the image cover the container while maintaining its aspect ratio. */
    }
    /* Media query for mobile devices */
    @media (max-width: 767px) {
        .team-img img {
            object-fit: contain; /* Change to 'contain' on mobile to show the full image */
            max-height: 250px; /* Optional: limit the height on mobile to prevent excessive spacing */
        }
    }
    </style>
<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>

    <!--==============================
    Breadcumb
    ==============================-->
    <div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Founding of NOK</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Founding of NOK</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!--==============================
    Service Area
    ==============================-->
    <section class="space-top">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-4 col-xxl-4 mb-30 pb-20 pb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                    <img src="{{ asset('nokw/assets/img/team/1.jpg') }}" style="height: 480px; object-fit: cover;" alt="Skill image">
                </div>
                <div class="col-lg-8 col-xxl-8 me-xl-auto">
                    <span class="sec-subtitle">
                        <i class="fas fa-bring-forward"></i>
                        FOUNDING OF THE NIGHTINGALES OF KUWAIT (NOK)
                    </span>
                    <h2 class="sec-title h1">From Vision to Reality</h2>
    <p class="mb-4 pb-1" style="text-align: justify;">
    The Nightingales of Kuwait (NOK) was founded by a dedicated group of visionary Indian nursing professionals 
     Mr. Sebastian Thomas, Mr. Sijumon Thomas, Mr. Nitheesh Narayanan, Mr. Nibu Pappachen, Mrs. Seena K Chacko, Mrs. Sajitha Scaria, and Mr. Mejit Chambakara
    — with the shared goal of creating a professional and supportive network for Indian nurses at Farwaniya Hospital, Kuwait.
    <br>
    <br>
    Motivated by the need for unity, collaboration and representation within the nursing community,
    the founders convened the first official meeting on June 20, 2016, at HI Dine Restaurant Hall, Jaleeb, Kuwait.
    This gathering led to the formal establishment of the association.
    <br>
    <br>
    Initially named the Farwaniya Indian Nurses Association (FINA), the organization reflected a focus
    on the local nursing community within Farwaniya Hospital. However, as the founders’ vision expanded
    to include the broader Indian nursing community across Kuwait, the association was renamed to
    Nightingales of Kuwait (NOK) to reflect its wider scope and mission.
    
    During the inaugural meeting, the first Executive Committee was constituted with Mr. Sebastian Thomas appointed as the Founding President. This marked the beginning of a dedicated
    journey to empower nurses, promote professional development and uphold excellence in healthcare
    throughout the State of Kuwait.
</p>
                </div>
            </div>
        </div>
    </section>

    <section style="padding-top: 40px;">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-8 col-xxl-8 me-xl-auto">
                    <h2 class="sec-title h2">Growth and Public Recognition</h2>
                    <p class="mb-4 pb-1">
                        Following the foundational leadership of Mr. Sebastian Thomas, Mrs. Seena Chacko assumed the role of the
                        second President, continuing to guide the association with vision and dedication. The first public event
                        organized by the Nightingales of Kuwait was the first Anniversary Celebration, <strong>Luminous 2017</strong>,
                        held on November 25, 2017, at United Indian School, Jaleeb, Kuwait.
                        <br><br>
                        The event was graced by distinguished guests, Mrs. Pushpa Susan George (Hon. Assistant Director of Nursing),
                        Mrs. Suja Mathew (Hon. Head Nurse in Surgical Department), and Adv. John Thomas (Manager, United Indian School, Jaleeb).
                        The celebration featured a vibrant cultural program, showcasing the talents and unity of the nursing community.
                        <br><br>
                        The success of this event drew significant public attention and many nurses expressed their enthusiastic
                        interest in joining the association’s initiatives. It marked a turning point in the association’s journey.
                        <br><br>
                        The founding executives have played a vital role in the growth and development of the association from its
                        inception to the present day. They are truly the backbone of our organization. The joint effort of our
                        founders and founding executives have been instrumental in establishing NOK as a visible and respected
                        platform for nurses in Kuwait.
                    </p>
                </div>
                <div class="col-lg-4 col-xxl-4 mb-30 pb-20 pb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                    <img src="{{ asset('nokw/assets/img/team/2.jpg') }}" style="height: 400px; object-fit: cover;" alt="Skill image">
                </div>
            </div>
        </div>
    </section>

      <!--==============================
        Team Area – OUR FOUNDERS
    ==============================-->
    <section class="space-top">
        <div class="container wow fadeInUp" data-wow-delay="0.2s">
            <div class="row justify-content-center text-center">
                <div class="col-xl-6">
                    <div class="title-area">
                        <h2 class="sec-title3 h1">OUR FOUNDERS</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @php
                    $founders = [
                        ['img' => 'team.png', 'name' => 'MR. SEBASTIAN THOMAS'],
                        ['img' => 'Mr-Sijumon-Thomas.JPG', 'name' => 'MR. SIJUMON THOMAS'],
                        ['img' => 'Mr-Nitheesh-Narayanan.JPG', 'name' => 'MR. NITHEESH NARAYANAN'],
                        ['img' => 'Mr.Nibu-Pappachen.jpeg', 'name' => 'MR. NIBU PAPPACHEN'],
                        ['img' => 'Mrs.Seena-Chacko.jpeg', 'name' => 'MRS. SEENA CHACKO'],
                        ['img' => 'MRS.SAJITHA-SCARIA.jpeg', 'name' => 'MRS. SAJITHA SCARIA'],
                        ['img' => 'MR.MEJIT-CHEMBAKARA.jpeg', 'name' => 'MR. MEJIT CHEMBAKARA'],
                    ];
                @endphp

                @foreach ($founders as $f)
                    <div class="col-xl-3">
                        <div class="team-style2">
                            <div class="team-img" style="height: 290px;">
                                <div class="team-shape1"></div>
                                <div class="team-shape2"></div>
                                <a><img src="{{ asset('nokw/assets/img/committeee/'.$f['img']) }}" alt="{{ $f['name'] }}"></a>
                            </div>
                            <div class="team-content">
                                <h3 class="team-title h5"><a class="text-inherit">{{ $f['name'] }}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!--==============================
        Founding Executives
    ==============================-->
    <section class="space-top space-extra-bottom">
        <div class="container wow fadeInUp" data-wow-delay="0.2s">
            <div class="row justify-content-center text-center">
                <div class="col-xl-6">
                    <div class="title-area">
                        <h2 class="sec-title3 h1">FOUNDING EXECUTIVES</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @php
                    $executives = [
                        ['img'=>'Mr-DennyThomas.jpg','name'=>'MR. DENNY THOMSON'],
                        ['img'=>'Mrs-Seema-Francis.jpg','name'=>'MRS. SEEMA FRANCIS'],
                        ['img'=>'team.png','name'=>'MR ABDUL SATHAR'],
                        ['img'=>'team.png','name'=>'MRS. ANNAMMA CHACKO'], 
                        ['img'=>'MR.SHINU-MATHEW.jpeg','name'=>'MR. SHINU MATHEW'], 
                        ['img'=>'Mrs-Soumya-Abraham.jpg','name'=>'MRS SAUMYA ABRAHAM'],
                        ['img'=>'Mrs-Prabha-Raveendran.jpg','name'=>'MRS PRABHA RAVEENDRAN'],
                        ['img'=>'Mr-Sudesh-Sudhakar.jpg','name'=>'MR SUDESH SUDHAKAR'],  
                        ['img'=>'team.png','name'=>'MRS. SREREKHA SAJESH'],
                        ['img'=>'Mr-Ciril-B-Mathew.jpg','name'=>'MR. CIRIL B MATHEW'],
                        ['img'=>'Mrs-Sumi-John.jpg','name'=>'MRS. SUMI JOHN'],
                        ['img'=>'Mrs-Bindu-Thankachan.jpg','name'=>'MRS. BINDU THANKACHAN'],
                        ['img'=>'Mrs-Shirin-Varghis.jpg','name'=>'MRS. SHIRIN VARGHIS'],
                        ['img'=>'Mrs-sheeja-Thomas.JPG','name'=>'MRS. SHEEJA THOMAS'],
                        ['img'=>'Mrs-Treesa-Abraham.jpg','name'=>'MRS. TREESA ABRAHAM'],
                        ['img'=>'Mr-Roy-yohannan.jpg','name'=>'MR. ROY YOHANNAN'],
                        ['img'=>'Aby-Chacko-Thomas.JPG','name'=>'MR. ABY CHACKO THOMAS'],
                        ['img'=>'Mr-Sobin-Thomas.jpg','name'=>'MR. SOBIN THOMAS'],
                        ['img'=>'Mrs-Sonia-Thomas.jpg','name'=>'MRS. SONIA THOMAS'],
                        ['img'=>'Mrs-Preetha-Thomas.jpg','name'=>'MRS. PREETHA THOMAS'],
                        ['img'=>'Mrs-Bindhumol-Subhash.jpg','name'=>'MRS. BINDHUMOL SUBASH'],
                        ['img'=>'Mr-Chinnappa-Dhas.jpg','name'=>'MR. CHINNAPPA DHAS'],
                        ['img'=>'Mr-Midhun-Abraham.jpg','name'=>'MR. MIDHUN ABRAHAM'], 
                        ['img'=>'Mrs-Teena-susan-Thankachan.jpg','name'=>'MRS. TEENA SUSAN THANKACHAN'],
                        ['img'=>'Mr-Shyju-Rajan.jpg','name'=>'MR. SHYJU RAJAN'],
                        ['img'=>'Mr-Sarath-Kumar-Sasidharan.PNG','name'=>'MR. SARATH KUMAR S'],
                        ['img'=>'Mr-Rojish-Skariah.jpg','name'=>'MR. ROJISH SKARIAH'],

                    ];
                @endphp

                @foreach ($executives as $e)
                    <div class="col-xl-3">
                        <div class="team-style2">
                            <div class="team-img" style="height: 260px; object-fit:contain;">
                                <div class="team-shape1"></div>
                                <div class="team-shape2"></div>
                                <a><img src="{{ asset('nokw/assets/img/committeee/'.$e['img']) }}" alt="{{ $e['name'] }}"></a>
                            </div>
                            <div class="team-content">
                                <h3 class="team-title h6"><a class="text-inherit">{{ $e['name'] }}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



@endsection
