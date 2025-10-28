<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of published events
     */
    public function index()
    {
        $events = Event::published()
            ->orderBy('event_date', 'desc')
            ->paginate(9);

        $featuredEvents = Event::published()
            ->featured()
            ->upcoming()
            ->take(3)
            ->get();

        return view('events', compact('events', 'featuredEvents'));
    }

    /**
     * Display a single event
     */
    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Get related events (same category, upcoming)
        $relatedEvents = Event::published()
            ->where('id', '!=', $event->id)
            ->where('category', $event->category)
            ->upcoming()
            ->take(3)
            ->get();

        return view('events_details', compact('event', 'relatedEvents'));
    }
}

