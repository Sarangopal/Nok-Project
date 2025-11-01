@extends('layouts.frontend')

@section('title', $event->title . ' | Nightingales of Kuwait')

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
            <h1 class="breadcumb-title">{{ $event->title }}</h1>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('events.index') }}">Events</a></li>
                    <li>{{ $event->title }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--==============================
    Event Details
===============================-->
<section class="vs-blog-wrapper blog-details space-top space-extra-bottom">
    <div class="container">
        <div class="row gx-40">
            <div class="col-lg-8">
                <div class="vs-blog blog-single">
                    {{-- Event Banner --}}
                    @if($event->banner_image)
                        <div class="blog-img">
                            <img src="{{ asset('storage/' . $event->banner_image) }}" style="width: 100%; height: 500px; object-fit: cover;" alt="{{ $event->title }}">
                        </div>
                    @endif

                    <div class="blog-content">
                        {{-- Event Meta --}}
                        <div class="blog-meta">
                            <a href="#"><i class="far fa-calendar"></i>{{ $event->event_date->format('d M, Y') }}</a>
                            @if($event->event_time)
                                <a href="#"><i class="fal fa-clock"></i>{{ $event->event_time }}</a>
                            @endif
                            @if($event->category)
                                <a href="#"><i class="fal fa-tag"></i>{{ $event->category }}</a>
                            @endif
                            @if($event->location)
                                <a href="#"><i class="fal fa-map-marker-alt"></i>{{ $event->location }}</a>
                            @endif
                        </div>

                        {{-- Event Title --}}
                        <h2 class="blog-title">{{ $event->title }}</h2>

                        {{-- Event Description (if exists) --}}
                        @if($event->description)
                            <div class="alert alert-info">
                                <strong>Summary:</strong> {{ $event->description }}
                            </div>
                        @endif

                        {{-- Event Full Content (Rich Editor Output) --}}
                        <div class="event-body">
                            {!! $event->body !!}
                        </div>

                        {{-- Social Share --}}
                        <div class="share-links clearfix mt-4">
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    @if($event->category)
                                        <span class="share-links-title">Category</span>
                                        <div class="tagcloud">
                                            <a href="#">{{ $event->category }}</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-auto text-end">
                                    <span class="share-links-title">Share Event</span>
                                    <ul class="social-links">
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event->slug)) }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(route('events.show', $event->slug)) }}&text={{ urlencode($event->title) }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . route('events.show', $event->slug)) }}" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Related Events Section --}}
                @if($relatedEvents->count() > 0)
                <div class="related-events mt-5">
                    <h3 class="h4 mb-4">Related Events</h3>
                    <div class="row">
                        @foreach($relatedEvents as $related)
                        <div class="col-md-6 mb-4">
                            <div class="vs-blog blog-style1">
                                <div class="blog-img">
                                    @if($related->banner_image)
                                        <img src="{{ asset('storage/' . $related->banner_image) }}" alt="{{ $related->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('nokw/assets/img/breadcumb/bannn.jpg') }}" alt="{{ $related->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta">
                                        <a href="#"><i class="far fa-calendar"></i>{{ $related->event_date->format('d M, Y') }}</a>
                                    </div>
                                    <h3 class="blog-title h6"><a href="{{ route('events.show', $related->slug) }}">{{ $related->title }}</a></h3>
                                    <a href="{{ route('events.show', $related->slug) }}" class="link-btn">Read Details<i class="far fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <aside class="sidebar-area">
                    {{-- Event Info Card --}}
                    <div class="widget">
                        <h3 class="widget_title">Event Information</h3>
                        <div class="card">
                            <div class="card-body">
                                @if($event->event_date)
                                    <p><strong><i class="far fa-calendar"></i> Date:</strong><br>{{ $event->event_date->format('l, F d, Y') }}</p>
                                @endif
                                @if($event->event_time)
                                    <p><strong><i class="fal fa-clock"></i> Time:</strong><br>{{ $event->event_time }}</p>
                                @endif
                                @if($event->location)
                                    <p><strong><i class="fal fa-map-marker-alt"></i> Location:</strong><br>{{ $event->location }}</p>
                                @endif
                                @if($event->category)
                                    <p><strong><i class="fal fa-tag"></i> Category:</strong><br>
                                        <span class="badge bg-primary">{{ $event->category }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Back to Events Button --}}
                    <div class="widget">
                        <a href="{{ route('events.index') }}" class="vs-btn style5" style="width:100%;">
                            <i class="far fa-arrow-left"></i> Back to All Events
                        </a>
                    </div>

                    {{-- Other Events --}}
                    @php
                        $otherEvents = \App\Models\Event::published()
                            ->where('id', '!=', $event->id)
                            ->upcoming()
                            ->take(5)
                            ->get();
                    @endphp

                    @if($otherEvents->count() > 0)
                    <div class="widget widget_categories">
                        <h3 class="widget_title">Upcoming Events</h3>
                        <ul>
                            @foreach($otherEvents as $other)
                            <li>
                                <a href="{{ route('events.show', $other->slug) }}">
                                    {{ $other->title }}
                                    <span class="text-muted small d-block">{{ $other->event_date->format('d M Y') }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</section>

@endsection
