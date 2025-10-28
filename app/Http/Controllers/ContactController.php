<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
     // Show the contact form page
    // public function show()
    // {
    //     $enquires = ContactMessage::all(); // fetch all records
    //     return view('pages.enquires', compact('enquires')); // pass to Blade view
       
    // }
    /**
     * Display a listing of all contact enquiries.
     */
    public function show()
    {
        // Fetch newest enquiries first
        $enquires = ContactMessage::latest()->get();

        // Pass the data to resources/views/pages/enquires.blade.php
        // return view('pages.enquires', compact('enquires'));
         return view('contact'); // Match the Blade filename
    }

    // Handle contact form submission
    public function submit(Request $request)
    {
        $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'phone'   => 'nullable|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:2000',
    ]);

    // Save the data to the database
    ContactMessage::create($validated);

    return back()->with('success', 'Your message has been saved successfully!');
    }
}
