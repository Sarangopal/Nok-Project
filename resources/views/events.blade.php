@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Events | Nightingales of Kuwait')

@php
use Illuminate\Support\Str;
@endphp

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
            <h1 class="breadcumb-title">Events</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Events</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--==============================
    All Events
===============================-->
<section class="vs-blog-wrapper space-top space-extra-bottom">
    <div class="container">
        @if($events->count() === 0)
            <div class="alert alert-info text-center" role="alert">
                <h4>No Events Available</h4>
                <p>There are currently no published events. Please check back soon!</p>
            </div>
        @else
            <div class="title-area text-center mb-40">
                <span class="sec-subtitle">What's Coming Up</span>
                <h2 class="sec-title h1">Upcoming Events</h2>
            </div>
            <div class="row">
                @foreach($events as $event)
                <div class="col-md-6 col-lg-4 mb-30">
                    <div class="vs-blog blog-style1">
                        <div class="blog-img">
                            @if($event->banner_image)
                                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" style="width: 387px; height: 320px; object-fit: cover;" class="w-100">
                            @else
                                @php
                                    $placeholderImages = [
                                        'Health & Wellness' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&h=600&fit=crop',
                                        'Celebration' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=800&h=600&fit=crop',
                                        'Professional Development' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=600&fit=crop',
                                        'Family' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=800&h=600&fit=crop',
                                        'Cultural' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=600&fit=crop',
                                        'Training' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=600&fit=crop',
                                        'Community Service' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800&h=600&fit=crop',
                                    ];
                                    $defaultImage = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop';
                                    $placeholderImage = $placeholderImages[$event->category] ?? $defaultImage;
                                @endphp
                                <img src="{{ $placeholderImage }}" alt="{{ $event->title }}" style="width: 387px; height: 320px; object-fit: cover;" class="w-100" onerror="this.src='{{ asset('nokw/assets/img/breadcumb/bannn.jpg') }}'">
                            @endif
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <a href="#"><i class="far fa-calendar"></i>{{ $event->event_date->format('d M, Y') }}</a>
                                @if($event->event_time)
                                    <a href="#"><i class="fal fa-clock"></i>{{ $event->event_time }}</a>
                                @endif
                            </div>
                            <h3 class="blog-title h5"><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></h3>
                            @if($event->description)
                                <p>{{ Str::limit($event->description, 100) }}</p>
                            @endif
                            @if($event->location)
                                <p class="text-muted"><i class="fal fa-map-marker-alt"></i> {{ $event->location }}</p>
                            @endif
                            <a href="{{ route('events.show', $event->slug) }}" class="link-btn">Read Details<i class="far fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($events->hasPages())
            <div class="pt-20 text-center">
                {{ $events->links('vendor.pagination.bootstrap-5') }}
            </div>
            @endif
        @endif
    </div>
</section>




@endsection
