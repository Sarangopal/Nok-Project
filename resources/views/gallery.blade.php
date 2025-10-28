@extends('layouts.frontend')

@section('title', 'Gallery | Nightingales of Kuwait')

@section('content')

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
            <h1 class="breadcumb-title">Gallery</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Gallery</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--==============================
    Gallery Section
==============================-->
<style>
    .project-img img {
        height: 300px;
        object-fit: cover;
        width: 100%;
    }
</style>

<div class="bg-smoke">
    <div class="container space-top space-extra-bottom">
        <!-- Category Filter Buttons -->
        <div class="filter-menu1 filter-menu-active wow fadeInUp" data-wow-delay="0.3s">
            <button data-filter="*" class="active">All</button>
            @foreach($categories as $category)
                <button data-filter=".{{ $category }}">{{ $categoryLabels[$category] ?? ucfirst($category) }}</button>
            @endforeach
        </div>

        <!-- Gallery Items -->
        <div class="row filter-active2 wow fadeInUp" data-wow-delay="0.4s">
            @forelse($galleries as $gallery)
                <div class="col-md-6 col-lg-4 filter-item {{ $gallery->category }}">
                    <div class="project-style2">
                        <div class="project-img">
                            <div class="project-shape"></div>
                            <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}">
                            <a href="{{ $gallery->image_url }}" class="icon-btn style4 popup-image">
                                <i class="far fa-search"></i>
                            </a>
                        </div>
                        <div class="project-content">
                            <span class="project-label">{{ $gallery->year }}</span>
                            <h3 class="project-title h5">
                                <a href="#" class="text-reset">{{ $gallery->title }}</a>
                            </h3>
                            @if($gallery->description)
                                <p class="project-desc">{{ \Illuminate\Support\Str::limit($gallery->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>No Gallery Items Yet</h4>
                        <p>Gallery images will appear here once they are added by the administrator.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
